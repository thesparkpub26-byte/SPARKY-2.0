<?php

namespace Database\Support;

use Illuminate\Support\Facades\DB;

/**
 * Changes the allowed values of an existing enum column, on MySQL / MariaDB and on PostgreSQL. Laravel has no
 * portable way to do this: MySQL has a real ENUM type, PostgreSQL gets a text column with a CHECK constraint.
 */
class EnumColumn
{
    /** Changes the allowed values of an ENUM column in a way that works on both MySQL and PostgreSQL. */
    public static function change(string $table, string $column, array $values, string $default, bool $nullable = false): void
    {
        // Table and column names cannot be bound as "?" values, so only plain names are accepted
        foreach ([$table, $column, $default, ...$values] as $name) {
            if (!preg_match('/^[a-z_]+$/', $name)) {
                throw new \InvalidArgumentException("Unexpected name '{$name}'");
            }
        }

        $list = implode(',', array_map(fn ($value) => "'" . str_replace("'", "''", $value) . "'", $values));
        $driver = DB::getDriverName();

        if (in_array($driver, ['mysql', 'mariadb'], true)) {
            DB::statement("ALTER TABLE {$table} MODIFY COLUMN {$column} ENUM({$list})" . ($nullable ? '' : ' NOT NULL') . " DEFAULT '{$default}'");
        } elseif ($driver === 'pgsql') {
            DB::statement("ALTER TABLE {$table} DROP CONSTRAINT IF EXISTS {$table}_{$column}_check");
            DB::statement("ALTER TABLE {$table} ADD CONSTRAINT {$table}_{$column}_check CHECK ({$column} IN ({$list}))");
            DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} SET DEFAULT '{$default}'");
            DB::statement("ALTER TABLE {$table} ALTER COLUMN {$column} " . ($nullable ? 'DROP NOT NULL' : 'SET NOT NULL'));
        }
    }
}
