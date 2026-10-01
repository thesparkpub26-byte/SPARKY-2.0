<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;

class CopyDataToDatabase extends Command
{
    protected $signature = 'data:copy
        {url : Where to copy to, e.g. "postgresql://postgres.abc:PASSWORD@host.pooler.supabase.com:5432/postgres" (special characters in the password must be percent-encoded)}
        {--force : Copy even if the target already has rows in some tables}
        {--resume : Carry on after an interrupted copy: tables that already have all their rows are skipped, the others are redone}';

    protected $description = 'Copy every row (and uploaded file) from this site\'s current database into another one, e.g. MySQL to Supabase';

    /** Nothing worth carrying over: rebuilt by the site, or already created by `migrate`. */
    private const SKIP = ['migrations', 'cache', 'cache_locks'];

    /** Tables whose starting rows are created by the migrations. */
    private const SEEDED = ['sections'];

    private const BATCH = 100;

    /**
     * Copies every table from the current database into the PostgreSQL database given by the URL (used for the
     * move to Supabase).
     */
    public function handle(): int
    {
        config(['database.connections.copy_target' => [
            'driver'         => 'pgsql',
            'url'            => $this->argument('url'),
            'charset'        => 'utf8',
            'prefix'         => '',
            'prefix_indexes' => true,
            'search_path'    => 'public',
            'sslmode'        => 'prefer',
        ]]);

        $source = DB::connection();
        $target = DB::connection('copy_target');
        $this->line("Copying from {$source->getDriverName()} '{$source->getDatabaseName()}' to {$target->getDriverName()} '{$target->getDatabaseName()}'");

        $tables = $this->orderedTables($source);
        // The target's tables come from `migrate`, so they are the site's own. Anything else in the source
        // (phpMyAdmin's pma__ tables, say) stays behind.
        $left = array_filter($tables, fn ($table) => !Schema::connection('copy_target')->hasTable($table));
        $tables = array_values(array_diff($tables, $left));
        if (!$tables) {
            $this->error('The target has none of the site\'s tables. Run `php artisan migrate --force` against it first.');

            return self::FAILURE;
        }
        if ($left) {
            $this->warn('Not copied, because the target has no such table: ' . implode(', ', $left));
        }

        // `migrate` itself puts the standard sections into a new database; those are replaced by the copied ones
        $filled = array_filter($tables, fn ($table) => !in_array($table, self::SEEDED, true) && $target->table($table)->exists());
        if ($filled && !$this->option('force') && !$this->option('resume')) {
            $this->error('The target already has rows in: ' . implode(', ', $filled) . '. Nothing was copied (use --force to replace them, or --resume after an interrupted copy).');

            return self::FAILURE;
        }

        // Every table is copied in its own transaction and tried again if the connection drops, so one long
        // transfer over a slow link does not have to survive in one piece.
        if (!$this->option('resume')) {
            foreach (array_reverse($tables) as $table) {
                $target->table($table)->delete();
            }
        }

        $counts = [];
        foreach ($tables as $table) {
            $total = $source->table($table)->count();
            if ($this->option('resume') && !in_array($table, self::SEEDED, true) && $target->table($table)->count() === $total) {
                $counts[$table] = $total;
                $this->line("  {$table}: already complete ({$total})");

                continue;
            }
            $counts[$table] = $this->copyTableWithRetry($source, $target, $table);
            $this->line("  {$table}: copied {$counts[$table]}");
        }

        if ($target->getDriverName() === 'pgsql') {
            foreach ($tables as $table) {
                if (Schema::connection('copy_target')->hasColumn($table, 'id')) {
                    // so the next row the site creates gets an id after the copied ones
                    $target->table($table)->selectRaw('setval(pg_get_serial_sequence(?, ?), coalesce(max(id), 1), max(id) is not null)', [$table, 'id'])->first();
                }
            }
        }

        $rows = [];
        $bad = 0;
        foreach ($tables as $table) {
            $there = $target->table($table)->count();
            $bad += $there !== $counts[$table] ? 1 : 0;
            $rows[] = [$table, $counts[$table], $there, $there === $counts[$table] ? 'ok' : 'DIFFERENT'];
        }
        $this->table(['Table', 'Copied', 'In target', ''], $rows);

        if ($bad) {
            $this->error("{$bad} tables differ.");

            return self::FAILURE;
        }
        $this->info('Done: every table has the same number of rows in the target.');

        return self::SUCCESS;
    }

    /** Tables in an order that respects foreign keys (parents before the tables that point at them). */
    private function orderedTables(Connection $source): array
    {
        $pending = [];
        foreach (Schema::getTables() as $table) {
            if (!in_array($table['name'], self::SKIP, true)) {
                $pending[$table['name']] = array_values(array_diff(array_column(Schema::getForeignKeys($table['name']), 'foreign_table'), [$table['name']]));
            }
        }

        $ordered = [];
        while ($pending) {
            $ready = array_keys(array_filter($pending, fn ($needs) => !array_diff($needs, $ordered)));
            if (!$ready) {
                $ready = [array_key_first($pending)];   // a loop of references: copy it anyway
            }
            foreach ($ready as $name) {
                $ordered[] = $name;
                unset($pending[$name]);
            }
        }

        return $ordered;
    }

    /** One table in one transaction; if the connection drops, reconnects and starts that table over (up to 5 tries). */
    private function copyTableWithRetry(Connection $source, Connection $target, string $table): int
    {
        for ($try = 1; ; $try++) {
            try {
                return $target->transaction(function () use ($source, $target, $table) {
                    $target->table($table)->delete();

                    return $this->copyTable($source, $target, $table);
                });
            } catch (\Throwable $e) {
                if ($try >= 5) {
                    throw $e;
                }
                $this->warn("  {$table}: attempt {$try} failed ({$e->getMessage()}); trying again");
                $target->disconnect();
                $target->reconnect();
            }
        }
    }

    /** Copies the rows of one table to the target database in batches, keeping binary columns (file chunks) intact. */
    private function copyTable(Connection $source, Connection $target, string $table): int
    {
        $binary = array_column(array_filter(
            Schema::connection('copy_target')->getColumns($table),
            fn ($column) => in_array($column['type_name'], ['bytea', 'blob', 'longblob', 'mediumblob'], true)
        ), 'name');

        $copied = 0;
        $write = function ($rows) use ($target, $table, $binary, &$copied) {
            $rows = $rows->map(fn ($row) => (array) $row)->all();
            $binary ? $this->insertWithBinary($target, $table, $rows, $binary) : $target->table($table)->insert($rows);
            $copied += count($rows);
        };

        if (Schema::hasColumn($table, 'id')) {
            $source->table($table)->orderBy('id')->chunkById(self::BATCH, $write);
        } else {
            $source->table($table)->cursor()->chunk(self::BATCH)->each(fn ($rows) => $write($rows));
        }

        return $copied;
    }

    /** PostgreSQL only accepts binary data bound as a stream (PDO::PARAM_LOB), so these rows go in one by one. */
    private function insertWithBinary(Connection $target, string $table, array $rows, array $binary): void
    {
        $grammar = $target->getQueryGrammar();

        foreach ($rows as $row) {
            $statement = $target->getPdo()->prepare(
                'insert into ' . $grammar->wrapTable($table) . ' (' . $grammar->columnize(array_keys($row)) . ') values (' . implode(', ', array_fill(0, count($row), '?')) . ')'
            );

            $streams = [];
            foreach (array_values($row) as $i => $value) {
                if (in_array(array_keys($row)[$i], $binary, true) && $value !== null) {
                    $stream = fopen('php://temp', 'r+');
                    fwrite($stream, $value);
                    rewind($stream);
                    $streams[] = $stream;
                    $statement->bindValue($i + 1, $stream, PDO::PARAM_LOB);
                } else {
                    $statement->bindValue($i + 1, $value);
                }
            }
            $statement->execute();

            foreach ($streams as $stream) {
                fclose($stream);
            }
        }
    }
}
