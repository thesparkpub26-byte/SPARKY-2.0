<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Notification;
use App\Models\Section;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /** Fills a fresh database with sample sections, users, articles and tasks for development. */
    public function run(): void
    {
        // ── Sections ──────────────────────────────────────────────
        $news     = Section::create(['name' => 'News',            'description' => 'Breaking news and current events',      'color' => '#3B82F6']);
        $features = Section::create(['name' => 'Features',        'description' => 'In-depth feature stories',              'color' => '#8B5CF6']);
        $sports   = Section::create(['name' => 'Sports',          'description' => 'Sports coverage and updates',           'color' => '#10B981']);
        $arts     = Section::create(['name' => 'Arts & Culture',  'description' => 'Arts, culture, and entertainment',      'color' => '#F59E0B']);
        $opinion  = Section::create(['name' => 'Opinion',         'description' => 'Editorials and opinion pieces',         'color' => '#EF4444']);

        // ── Users ─────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'System Administrator',
            'email'    => 'admin@sparky.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $eic = User::create([
            'name'     => 'Maria Santos',
            'email'    => 'eic@sparky.com',
            'password' => Hash::make('password'),
            'role'     => 'eic',
        ]);

        $sectionEditorNews = User::create([
            'name'       => 'Jose Reyes',
            'email'      => 'editor.news@sparky.com',
            'password'   => Hash::make('password'),
            'role'       => 'section_editor',
        ]);

        $sectionEditorFeatures = User::create([
            'name'       => 'Ana Cruz',
            'email'      => 'editor.features@sparky.com',
            'password'   => Hash::make('password'),
            'role'       => 'section_editor',
        ]);

        $sectionEditorSports = User::create([
            'name'       => 'Carlos Mendoza',
            'email'      => 'editor.sports@sparky.com',
            'password'   => Hash::make('password'),
            'role'       => 'section_editor',
        ]);

        $writer1 = User::create([
            'name'       => 'Liza Ramos',
            'email'      => 'writer1@sparky.com',
            'password'   => Hash::make('password'),
            'role'       => 'staff_writer',
        ]);

        $writer2 = User::create([
            'name'       => 'Marco Dela Cruz',
            'email'      => 'writer2@sparky.com',
            'password'   => Hash::make('password'),
            'role'       => 'staff_writer',
        ]);

        $artist1 = User::create([
            'name'       => 'Sofia Villanueva',
            'email'      => 'artist1@sparky.com',
            'password'   => Hash::make('password'),
            'role'       => 'staff_artist',
        ]);

        $artist2 = User::create([
            'name'       => 'Diego Torres',
            'email'      => 'artist2@sparky.com',
            'password'   => Hash::make('password'),
            'role'       => 'staff_artist',
        ]);

        // ── Articles ──────────────────────────────────────────────
        $article1 = Article::create([
            'title'      => 'University Budget Cuts Impact Student Programs',
            'content'    => 'The administration announced significant budget cuts affecting various student programs this semester...',
            'excerpt'    => 'Budget cuts announced by the administration will significantly affect student services and programs.',
            'author_id'  => $writer1->id,
            'section_id' => $news->id,
            'status'     => Article::STATUS_SUBMITTED,
            'type'       => Article::TYPE_ARTICLE,
            'word_count' => 850,
            'submitted_at' => now()->subDays(2),
        ]);

        $article2 = Article::create([
            'title'      => 'SPARK Alumni: Where Are They Now?',
            'content'    => 'Former staff members of The SPARK have gone on to achieve remarkable success in journalism...',
            'excerpt'    => 'A look back at SPARK alumni who have made their mark in media and beyond.',
            'author_id'  => $writer2->id,
            'section_id' => $features->id,
            'status'     => Article::STATUS_ENDORSED,
            'type'       => Article::TYPE_FEATURE,
            'word_count' => 1200,
            'submitted_at' => now()->subDays(5),
            'endorsed_at'  => now()->subDays(3),
        ]);

        $article3 = Article::create([
            'title'      => 'UAAP Season Preview: Key Players to Watch',
            'content'    => 'As the UAAP season kicks off, analysts and coaches have their eyes on several standout players...',
            'excerpt'    => 'Preview of the upcoming UAAP season with key players and predictions.',
            'author_id'  => $writer1->id,
            'section_id' => $sports->id,
            'status'     => Article::STATUS_APPROVED,
            'type'       => Article::TYPE_ARTICLE,
            'word_count' => 950,
            'submitted_at' => now()->subDays(7),
            'endorsed_at'  => now()->subDays(6),
            'approved_at'  => now()->subDays(4),
        ]);

        $article4 = Article::create([
            'title'      => 'My Draft Article on Campus Life',
            'content'    => 'Draft content...',
            'excerpt'    => 'A personal piece about campus life.',
            'author_id'  => $writer2->id,
            'section_id' => $features->id,
            'status'     => Article::STATUS_DRAFT,
            'type'       => Article::TYPE_OPINION,
            'word_count' => 300,
        ]);

        // ── Tasks ─────────────────────────────────────────────────
        $task1 = Task::create([
            'title'             => 'Write article on tuition fee increase',
            'description'       => 'Cover the recent tuition fee increase announcement and its impact on students.',
            'article_id'        => null,
            'assignee_id'       => $writer1->id,
            'assigned_by'       => $sectionEditorNews->id,
            'section_id'        => $news->id,
            'type'              => Task::TYPE_WRITING,
            'priority'          => Task::PRIORITY_HIGH,
            'status'            => Task::STATUS_IN_PROGRESS,
            'deadline'          => now()->addDays(5)->toDateString(),
        ]);

        $task2 = Task::create([
            'title'       => 'Create illustration for UAAP feature',
            'description' => 'Design a full-page illustration depicting the UAAP championship atmosphere.',
            'assignee_id' => $artist2->id,
            'assigned_by' => $sectionEditorSports->id,
            'section_id'  => $sports->id,
            'type'        => Task::TYPE_ILLUSTRATION,
            'priority'    => Task::PRIORITY_MEDIUM,
            'status'      => Task::STATUS_PENDING,
            'deadline'    => now()->addDays(7)->toDateString(),
        ]);

        $task3 = Task::create([
            'title'       => 'Photograph the University Foundation Day event',
            'description' => 'Capture key moments from the Foundation Day celebration.',
            'assignee_id' => $artist1->id,
            'assigned_by' => $sectionEditorNews->id,
            'section_id'  => $news->id,
            'type'        => Task::TYPE_PHOTOGRAPHY,
            'priority'    => Task::PRIORITY_URGENT,
            'status'      => Task::STATUS_SUBMITTED,
            'deadline'    => now()->addDays(1)->toDateString(),
        ]);

        $task4 = Task::create([
            'title'       => 'Write opinion piece on campus sustainability',
            'description' => 'Share your perspective on what the university can do to become more eco-friendly.',
            'assignee_id' => $writer2->id,
            'assigned_by' => $sectionEditorFeatures->id,
            'section_id'  => $features->id,
            'type'        => Task::TYPE_WRITING,
            'priority'    => Task::PRIORITY_LOW,
            'status'      => Task::STATUS_COMPLETED,
            'deadline'    => now()->subDays(2)->toDateString(),
            'completed_at' => now()->subDays(1),
        ]);

        $this->command->info('✅ Database seeded successfully!');
        $this->command->table(
            ['Resource', 'Count'],
            [
                ['Sections',      Section::count()],
                ['Users',         User::count()],
                ['Articles',      Article::count()],
                ['Tasks',         Task::count()],
            ]
        );
        $this->command->newLine();
        $this->command->info('Test login credentials (password: password):');
        $this->command->table(
            ['Role', 'Email'],
            [
                ['Admin',          'admin@sparky.com'],
                ['Editor-in-Chief','eic@sparky.com'],
                ['Section Editor', 'editor.news@sparky.com'],
                ['Staff Writer',   'writer1@sparky.com'],
                ['Staff Artist',   'artist1@sparky.com'],
            ]
        );
    }
}
