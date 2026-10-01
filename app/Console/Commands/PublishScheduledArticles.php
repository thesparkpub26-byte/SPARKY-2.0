<?php

namespace App\Console\Commands;

use App\Models\Article;
use Illuminate\Console\Command;

class PublishScheduledArticles extends Command
{
    protected $signature = 'articles:publish-scheduled';

    protected $description = 'Publish scheduled articles and videos whose publish time has arrived';

    /** Publishes the scheduled articles and videos whose publish time has arrived. */
    public function handle(): int
    {
        $count = Article::publishDue();

        if ($count) {
            $this->info("Published {$count} scheduled " . ($count === 1 ? 'item' : 'items') . '.');
        }

        return self::SUCCESS;
    }
}
