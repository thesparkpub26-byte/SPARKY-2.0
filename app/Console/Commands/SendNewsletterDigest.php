<?php

namespace App\Console\Commands;

use App\Http\Controllers\ArticleController;
use App\Mail\NewsletterDigestMail;
use App\Models\Article;
use App\Models\NewsletterSubscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendNewsletterDigest extends Command
{
    protected $signature = 'newsletter:digest {--days=7 : How many days back to look for published articles}';

    protected $description = 'Email subscribers the articles published recently';

    /** Emails the week's published articles to every active subscriber; sends nothing when nothing was published. */
    public function handle(ArticleController $cards): int
    {
        Article::publishDue();

        $articles = Article::with('section:id,name')
            ->where('status', Article::STATUS_PUBLISHED)
            ->where('type', '!=', Article::TYPE_VIDEO)
            ->where('published_at', '>=', now()->subDays(max(1, (int) $this->option('days'))))
            ->orderByDesc('published_at')
            ->limit(8)
            ->get();

        if ($articles->isEmpty()) {
            $this->info('No new articles, so no newsletter was sent.');
            return self::SUCCESS;
        }

        $stories = $articles->map(function (Article $a) use ($cards) {
            $card = $cards->toCard($a);

            return [
                'title'    => $card['title'],
                'excerpt'  => $card['excerpt'],
                'category' => $card['badge'],
                'date'     => $card['date'],
                'url'      => url('/article/' . $a->id),
                'image'    => $card['image'] ? (str_starts_with($card['image'], 'http') ? $card['image'] : url($card['image'])) : null,
            ];
        })->all();

        $sent = 0;
        NewsletterSubscriber::active()->each(function (NewsletterSubscriber $subscriber) use ($stories, &$sent) {
            try {
                Mail::to($subscriber->email)->send(new NewsletterDigestMail($subscriber, $stories));
                $sent++;
            } catch (\Throwable $e) {
                Log::error('Newsletter digest failed for subscriber ' . $subscriber->id . ': ' . $e->getMessage());
            }
        });

        $this->info("Sent the newsletter ({$articles->count()} stories) to {$sent} subscribers.");

        return self::SUCCESS;
    }
}
