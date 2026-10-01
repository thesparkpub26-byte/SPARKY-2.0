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

    /** Creates the standard sections if they do not exist yet. */
    public function up(): void
    {
        // Without events: saving a section clears the reader cache, and on a fresh database with
        // CACHE_STORE=database the cache table is only created by a later migration
        Section::withoutEvents(function () {
            foreach ($this->canonicalSections as $name) {
                Section::firstOrCreate(['name' => $name]);
            }
        });
    }

    /** Does nothing: other records may already use the seeded sections. */
    public function down(): void
    {
        // Leave the seeded rows in place — other records may now reference them.
    }
};
