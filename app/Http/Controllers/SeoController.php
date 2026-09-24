<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Support\Str;

/**
 * What search engines and social networks see. The site is a single-page app, so link previews
 * (Facebook, Messenger, X, ...) only work if the server puts the article's details in the HTML itself.
 */
class SeoController extends Controller
{
    private const SITE_NAME = 'TheSPARK';
    private const DEFAULT_DESCRIPTION = 'TheSPARK is the official student publication of Camarines Sur Polytechnic Colleges. Truth knows no limits.';

    /** The app page for /article/{id}, with that article's title, summary and picture in the head. */
    public function article(string $id)
    {
        $article = Article::with('section:id,name')
            ->where('status', Article::STATUS_PUBLISHED)
            ->where('type', '!=', Article::TYPE_VIDEO)
            ->find($id);

        if (!$article) {
            return response()->view('app', ['meta' => []]);
        }

        $plain = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags(str_replace(['</p>', '<br>', '<br/>', '</div>'], ' ', (string) $article->content)))));
        $image = $article->cover_image ?: ($article->media_files[0] ?? null);

        return response()->view('app', ['meta' => [
            'title'       => $article->title . ' | ' . self::SITE_NAME,
            'description' => Str::limit($article->excerpt ?: $plain, 200) ?: self::DEFAULT_DESCRIPTION,
            'image'       => $image ? (str_starts_with($image, 'http') ? $image : url($image)) : null,
            'url'         => url('/article/' . $article->id),
            'type'        => 'article',
            'published'   => ($article->published_at ?? $article->created_at)?->toIso8601String(),
            'section'     => $article->section?->name,
        ]]);
    }

    public function robots()
    {
        // Staff pages are for staff; the rest of the site is open to search engines
        $body = implode("\n", [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /eic',
            'Disallow: /editor',
            'Disallow: /writer',
            'Disallow: /artist',
            'Disallow: /broadcaster',
            'Disallow: /monitoring-sheet',
            'Disallow: /api/',
            'Allow: /',
            '',
            'Sitemap: ' . url('/sitemap.xml'),
            '',
        ]);

        return response($body, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    public function sitemap()
    {
        Article::publishDue();

        $pages = collect(['/', '/categories', '/videos', '/gallery', '/issues'])
            ->map(fn (string $path) => ['loc' => url($path), 'lastmod' => null]);

        $articles = Article::where('status', Article::STATUS_PUBLISHED)
            ->where('type', '!=', Article::TYPE_VIDEO)
            ->orderByDesc('published_at')
            ->limit(5000)
            ->get(['id', 'published_at', 'updated_at'])
            ->map(fn (Article $a) => [
                'loc'     => url('/article/' . $a->id),
                'lastmod' => ($a->updated_at ?? $a->published_at)?->toAtomString(),
            ]);

        $xml = view('sitemap', ['urls' => $pages->concat($articles)])->render();

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }
}
