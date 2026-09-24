<?php

use App\Models\Section;
use Illuminate\Database\Migrations\Migration;

// The `sections` table had stale/duplicate seed data that never matched the
// real category taxonomy already used everywhere else in the app (task
// assignment, endorsement filters, etc). This ensures each canonical
// category has exactly one real row, so it can be referenced by section_id.
return new class extends Migration
{
    private array $canonicalSections = [
        'News', 'Opinion', 'Editorial', 'Feature', 'Sci-Tech',
        'DevCom', 'Sports', 'Literary', 'Video',
    ];

    public function up(): void
    {
        foreach ($this->canonicalSections as $name) {
            Section::firstOrCreate(['name' => $name]);
        }
    }

    public function down(): void
    {
        // Leave the seeded rows in place — other records may now reference them.
    }
};
