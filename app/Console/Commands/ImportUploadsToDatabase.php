<?php

namespace App\Console\Commands;

use App\Models\StoredFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ImportUploadsToDatabase extends Command
{
    protected $signature = 'uploads:import {--dry-run : Only report what would be imported, change nothing}';

    protected $description = 'Copy every uploaded photo and PDF that the site uses from storage/app/public into the database';

    /** Columns that hold a bare path such as "profile_pictures/abc.jpg" (the site adds /storage/ itself). */
    private const PATH_COLUMNS = [
        'users'            => 'profile_picture',
        'gallery_photos'   => 'image_path',
        'published_issues' => 'pdf_path',
    ];

    /** Tables that can never hold a reference to an upload. */
    private const SKIP_TABLES = ['cache', 'cache_locks', 'migrations', 'personal_access_tokens', 'stored_files', 'stored_file_chunks'];

    public function handle(): int
    {
        $dry = (bool) $this->option('dry-run');
        $root = storage_path('app/public');

        $references = $this->findReferences();
        ksort($references);

        $rows = [];
        $counts = ['imported' => 0, 'already' => 0, 'missing' => 0, 'mismatch' => 0];
        $bytes = 0;

        foreach ($references as $path => $usedBy) {
            $source = $root . '/' . $path;
            $onDisk = is_file($source);
            $stored = StoredFile::where('path', $path)->first();

            if ($stored) {
                $status = 'already in database';
                $counts['already']++;
            } elseif (!$onDisk) {
                $status = 'FILE MISSING';
                $counts['missing']++;
            } elseif ($dry) {
                $status = 'would import';
            } else {
                $stream = fopen($source, 'r');
                Storage::disk('public')->writeStream($path, $stream);
                fclose($stream);
                $stored = StoredFile::where('path', $path)->first();
                $status = 'imported';
                $counts['imported']++;
                $bytes += filesize($source);
            }

            // Prove the copy is complete: same size and checksum as the original
            if ($stored && $onDisk && ((int) $stored->size !== filesize($source) || $stored->sha1 !== sha1_file($source))) {
                $status = 'MISMATCH with the file on disk';
                $counts['mismatch']++;
            }

            $rows[] = [$path, $status, implode(', ', array_slice($usedBy, 0, 3)) . (count($usedBy) > 3 ? ' +' . (count($usedBy) - 3) . ' more' : '')];
        }

        $this->table(['File', 'Status', 'Used by'], $rows);
        $this->line(sprintf(
            '%d files in use: %d imported (%s), %d already in the database, %d missing on disk, %d mismatched.',
            count($references), $counts['imported'], $this->size($bytes), $counts['already'], $counts['missing'], $counts['mismatch']
        ));

        $unused = $this->unusedFiles($root, array_keys($references));
        if ($unused) {
            $this->line(sprintf('%d files in storage/app/public are not used by anything and were left out (%s).', count($unused), $this->size(array_sum(array_map('filesize', array_map(fn ($f) => $root . '/' . $f, $unused))))));
        }
        if ($counts['missing']) {
            $this->warn('Rows marked FILE MISSING already pointed at a file that no longer exists; they were broken before this import and are unchanged.');
        }

        return $counts['mismatch'] ? self::FAILURE : self::SUCCESS;
    }

    /** @return array<string, string[]> upload path => the rows that use it, e.g. "articles#4.media_files" */
    private function findReferences(): array
    {
        $found = [];
        $add = function (string $path, string $where) use (&$found) {
            $path = ltrim(str_replace('\\/', '/', $path), '/');
            if ($path !== '' && !str_contains($path, '..')) {
                $found[$path][] = $where;
            }
        };

        foreach (self::PATH_COLUMNS as $table => $column) {
            foreach (DB::table($table)->whereNotNull($column)->where($column, '!=', '')->get(['id', $column]) as $row) {
                $add(preg_replace('#^/?storage/#', '', $row->$column), "{$table}#{$row->id}.{$column}");
            }
        }

        // Everywhere else the site stores the address itself ("/storage/article-media/x.jpg", escaped
        // as "\/storage\/..." inside JSON), so look through every text column
        foreach (Schema::getTables() as $table) {
            $name = $table['name'];
            if (in_array($name, self::SKIP_TABLES, true) || !Schema::hasColumn($name, 'id')) {
                continue;
            }
            foreach (Schema::getColumns($name) as $column) {
                if (!preg_match('/char|text|json/i', $column['type_name'])) {
                    continue;
                }
                $rows = DB::table($name)->where($column['name'], 'like', '%storage%')->get(['id', $column['name']]);
                foreach ($rows as $row) {
                    preg_match_all('#/storage\\\\?/([A-Za-z0-9_\-]+(?:\\\\?/[A-Za-z0-9_.\-]+)+)#', (string) $row->{$column['name']}, $matches);
                    foreach ($matches[1] as $path) {
                        $add($path, "{$name}#{$row->id}.{$column['name']}");
                    }
                }
            }
        }

        return array_map('array_values', array_map('array_unique', $found));
    }

    /** @return string[] files under storage/app/public that no row refers to */
    private function unusedFiles(string $root, array $used): array
    {
        $unused = [];
        if (!is_dir($root)) {
            return $unused;
        }
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root, \FilesystemIterator::SKIP_DOTS));
        foreach ($iterator as $file) {
            $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
            if ($file->isFile() && basename($relative) !== '.gitignore' && !in_array($relative, $used, true)) {
                $unused[] = $relative;
            }
        }

        return $unused;
    }

    private function size(int $bytes): string
    {
        return $bytes >= 1048576 ? round($bytes / 1048576, 1) . ' MB' : round($bytes / 1024) . ' KB';
    }
}
