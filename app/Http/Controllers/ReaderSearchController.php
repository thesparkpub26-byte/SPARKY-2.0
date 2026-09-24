<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\GalleryPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Public "smart search" for the reader site: articles, videos and gallery photos.
 *
 * It goes beyond a plain LIKE: the query is split into meaningful words, matched on word stems
 * ("elections" finds "election"), widened with related words ("exam" also finds "quiz"), weighted by
 * where the hit is (title > category/author > summary > body), and, when nothing matches, retried
 * with a spelling correction taken from the site's own titles.
 */
class ReaderSearchController extends Controller
{
    private const MAX_TERMS   = 6;
    private const MAX_RESULTS = 30;
    private const CANDIDATES  = 300;
    private const FULLTEXT_INDEX = 'articles_fulltext';

    private const STOPWORDS = [
        'a', 'an', 'and', 'are', 'as', 'at', 'be', 'by', 'for', 'from', 'how', 'in', 'is', 'it', 'of', 'on', 'or',
        'that', 'the', 'this', 'to', 'was', 'were', 'what', 'when', 'where', 'which', 'who', 'why', 'with', 'about',
        'latest', 'recent', 'any', 'some', 'show', 'me', 'find', 'articles', 'article', 'stories',
    ];

    /** Words the database's full-text index ignores, so they are looked up with LIKE instead. */
    private const FULLTEXT_IGNORED = [
        'a', 'about', 'an', 'are', 'as', 'at', 'be', 'by', 'com', 'de', 'en', 'for', 'from', 'how', 'i', 'in', 'is',
        'it', 'la', 'of', 'on', 'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'where', 'who', 'will',
        'with', 'und', 'www',
    ];

    /** Words that are "connected": a hit on one counts (at a lower weight) for the others. */
    private const RELATED = [
        ['sports', 'sport', 'athletics', 'athlete', 'varsity', 'tournament', 'championship', 'intramurals', 'game'],
        ['exam', 'exams', 'examination', 'test', 'quiz', 'assessment'],
        ['student', 'students', 'learner', 'scholar'],
        ['teacher', 'faculty', 'professor', 'instructor', 'educator', 'mentor'],
        ['event', 'activity', 'celebration', 'festival', 'program'],
        ['scholarship', 'grant', 'tuition', 'financial'],
        ['technology', 'tech', 'science', 'innovation', 'digital', 'research'],
        ['art', 'artist', 'illustration', 'painting', 'drawing', 'exhibit'],
        ['poem', 'poetry', 'verse', 'literary', 'literature', 'prose'],
        ['election', 'campaign', 'vote', 'candidate', 'council', 'officer'],
        ['health', 'wellness', 'medical', 'mental', 'clinic'],
        ['campus', 'school', 'university', 'college'],
        ['announcement', 'advisory', 'memo', 'update', 'notice'],
        ['opinion', 'editorial', 'commentary', 'column', 'viewpoint'],
    ];

    private static ?bool $hasFulltext = null;

    /**
     * ?q= the words; ?type=articles|videos|photos (default: all three); ?category= (articles only);
     * ?period=week|month|year; ?sort=relevance|newest|oldest; ?page= and ?per_page= (max 30);
     * ?exact=1 searches the words as typed, without the spelling correction.
     */
    public function search(Request $request)
    {
        $raw      = trim((string) $request->query('q', ''));
        $category = strtolower(trim((string) $request->query('category', '')));
        $period   = (string) $request->query('period', '');
        $sort     = (string) $request->query('sort', 'relevance');
        $type     = (string) $request->query('type', '');
        $page     = max(1, (int) $request->query('page', 1));
        $perPage  = max(1, min(self::MAX_RESULTS, (int) $request->query('per_page', self::MAX_RESULTS)));

        $empty = [
            'query' => $raw, 'corrected_query' => null, 'highlight' => [], 'total' => 0, 'data' => [],
            'categories' => [], 'kinds' => ['article' => 0, 'video' => 0, 'photo' => 0],
            'current_page' => 1, 'last_page' => 1,
        ];
        if (mb_strlen($raw) < 2) {
            return response()->json($empty);
        }

        $terms = $this->terms($raw);
        if (!$terms) {
            return response()->json($empty);
        }

        $corrected = null;
        $hits = $this->rankAll($terms, $raw);
        if ($hits->isEmpty() && !$request->boolean('exact')) {
            $fixed = $this->correct($terms);
            if ($fixed !== $terms) {
                $corrected = implode(' ', $fixed);
                $terms     = $fixed;
                $hits      = $this->rankAll($terms, $corrected);
            }
        }

        // Time filter first, so the counts below reflect it
        $since = match ($period) {
            'week'  => now()->subWeek(),
            'month' => now()->subMonth(),
            'year'  => now()->subYear(),
            default => null,
        };
        if ($since) {
            $hits = $hits->filter(fn ($h) => $h['date'] && $h['date']->gte($since));
        }

        $kinds = [
            'article' => $hits->where('kind', 'article')->count(),
            'video'   => $hits->where('kind', 'video')->count(),
            'photo'   => $hits->where('kind', 'photo')->count(),
        ];

        $categories = $hits->where('kind', 'article')
            ->groupBy(fn ($h) => $h['category'] ?? 'Other')
            ->map(fn (Collection $g, string $name) => ['name' => $name, 'count' => $g->count()])
            ->sortByDesc('count')->values();

        $kindFilter = ['articles' => 'article', 'videos' => 'video', 'photos' => 'photo'][$type] ?? null;
        if ($category !== '') {
            // Only articles belong to a category
            $kindFilter = 'article';
            $names = $category === 'feature' ? ['feature', 'features'] : [$category];
            $hits  = $hits->filter(fn ($h) => in_array(strtolower((string) $h['category']), $names, true));
        }
        if ($kindFilter) {
            $hits = $hits->where('kind', $kindFilter);
        }

        $hits = match ($sort) {
            'newest' => $hits->sortByDesc(fn ($h) => $h['date']?->timestamp ?? 0),
            'oldest' => $hits->sortBy(fn ($h) => $h['date']?->timestamp ?? 0),
            default  => $hits->sort(fn ($a, $b) => [$b['score'], $b['date']?->timestamp ?? 0] <=> [$a['score'], $a['date']?->timestamp ?? 0]),
        };

        $total = $hits->count();

        return response()->json([
            'query'           => $raw,
            'corrected_query' => $corrected,
            'highlight'       => $this->needles($terms),
            'total'           => $total,
            'categories'      => $categories,
            'kinds'           => $kinds,
            'current_page'    => $page,
            'last_page'       => max(1, (int) ceil($total / $perPage)),
            'data'            => $hits->slice(($page - 1) * $perPage, $perPage)->map(fn ($h) => $this->card($h))->values(),
        ]);
    }

    /** The shape the reader pages show for one result. */
    private function card(array $h): array
    {
        $extra = ['kind' => $h['kind'], 'snippet' => $h['snippet'], 'matched_in' => $h['matched_in']];
        $articles = app(ArticleController::class);

        if ($h['kind'] === 'photo') {
            /** @var GalleryPhoto $p */
            $p = $h['model'];

            return $extra + [
                'id'     => $p->id,
                'badge'  => 'Photo',
                'title'  => $p->title,
                'image'  => $p->image_path ? '/storage/' . $p->image_path : null,
                'date'   => $p->created_at?->format('M j, Y'),
                'author' => $p->artist?->name,
            ];
        }

        /** @var Article $a */
        $a = $h['model'];
        $card = $h['kind'] === 'video' ? $articles->toVideoCard($a) : $articles->toCard($a);

        return $extra + $card + ['author' => $a->author?->name];
    }

    // ── Query understanding ────────────────────────────────────────────────────

    /** Lower-cased words of the query, without filler words, capped so a long paste can't get expensive. */
    private function terms(string $raw): array
    {
        $words = preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($raw), -1, PREG_SPLIT_NO_EMPTY);
        $words = array_values(array_unique(array_filter($words, fn ($w) => mb_strlen($w) >= 2)));

        $meaningful = array_values(array_filter($words, fn ($w) => !in_array($w, self::STOPWORDS, true)));

        return array_slice($meaningful ?: $words, 0, self::MAX_TERMS);
    }

    /** Crude English stemmer: enough to treat "elections/elected/election" alike. Used as a word prefix. */
    private function stem(string $w): string
    {
        foreach (['ies', 'ing', 'ed', 'es', 'ly', 's', 'y'] as $suffix) {
            if (!str_ends_with($w, $suffix)) continue;

            $s = mb_substr($w, 0, mb_strlen($w) - strlen($suffix));
            if (mb_strlen($s) < 4) continue;

            // "running" -> "runn" -> "run"
            if (in_array($suffix, ['ing', 'ed'], true) && mb_substr($s, -1) === mb_substr($s, -2, 1)) {
                $s = mb_substr($s, 0, -1);
            }

            return $s;
        }

        return $w;
    }

    /** The other words in the term's related-words group (empty when it has none). */
    private function related(string $term): array
    {
        $stem = $this->stem($term);
        foreach (self::RELATED as $group) {
            foreach ($group as $word) {
                if ($word === $term || $this->stem($word) === $stem) {
                    return array_values(array_diff($group, [$term]));
                }
            }
        }

        return [];
    }

    /** Every word stem we might match on: the terms and their related words. */
    private function needles(array $terms): array
    {
        $needles = [];
        foreach ($terms as $t) {
            foreach ([$t, ...$this->related($t)] as $n) {
                $needles[$this->stem($n)] = true;
            }
        }

        return array_keys($needles);
    }

    /** Replaces words that appear nowhere in our titles / categories / authors with the closest one that does. */
    private function correct(array $terms): array
    {
        $vocabulary = [];
        $add = function (string $text) use (&$vocabulary) {
            foreach (preg_split('/[^\p{L}\p{N}]+/u', mb_strtolower($text), -1, PREG_SPLIT_NO_EMPTY) as $w) {
                if (mb_strlen($w) >= 4) $vocabulary[$w] = true;
            }
        };

        Article::with(['section:id,name', 'author:id,name'])
            ->where('status', Article::STATUS_PUBLISHED)
            ->get(['id', 'title', 'section_id', 'author_id'])
            ->each(fn ($a) => $add($a->title . ' ' . $a->section?->name . ' ' . $a->author?->name));
        GalleryPhoto::with('artist:id,name')->get(['id', 'title', 'artist_id'])
            ->each(fn ($p) => $add($p->title . ' ' . $p->artist?->name));

        $vocabulary = array_keys($vocabulary);

        return array_map(function (string $term) use ($vocabulary) {
            if (mb_strlen($term) < 4 || !preg_match('/^[a-z0-9]+$/', $term)) return $term;

            $stem = $this->stem($term);
            foreach ($vocabulary as $word) {
                if (str_starts_with($word, $stem)) return $term; // already a real word here
            }

            $best = $term;
            $bestDistance = mb_strlen($term) <= 5 ? 2 : 3; // exclusive upper bound
            foreach ($vocabulary as $word) {
                if (!preg_match('/^[a-z0-9]+$/', $word)) continue;
                $d = levenshtein($term, $word);
                if ($d < $bestDistance) {
                    $best = $word;
                    $bestDistance = $d;
                }
            }

            return $best;
        }, $terms);
    }

    // ── Finding and scoring ────────────────────────────────────────────────────

    private function rankAll(array $terms, string $phrase): Collection
    {
        return $this->rankArticles($terms, $phrase)->concat($this->rankPhotos($terms, $phrase));
    }

    /**
     * Articles and videos.
     *
     * @return Collection<int, array{kind: string, model: Article, category: ?string, score: float, date: mixed, snippet: string, matched_in: array}>
     */
    private function rankArticles(array $terms, string $phrase): Collection
    {
        $needles = $this->needles($terms);

        $query = Article::with(['author:id,name', 'section:id,name'])
            ->where('status', Article::STATUS_PUBLISHED)
            ->where(function ($q) use ($needles) {
                $this->matchText($q, $needles);
                foreach ($needles as $n) {
                    $q->orWhereHas('section', fn ($s) => $s->whereLike('name', $this->like($n)))
                      ->orWhereHas('author', fn ($u) => $u->whereLike('name', $this->like($n)));
                }
            })
            ->orderByDesc('published_at')->orderByDesc('id')
            ->limit(self::CANDIDATES);

        $phrase   = mb_strtolower(trim($phrase));
        $minTerms = count($terms) >= 4 ? 2 : 1;

        return $query->get()->map(function (Article $a) use ($terms, $phrase, $minTerms) {
            $fields = [
                'title'   => mb_strtolower((string) $a->title),
                'section' => mb_strtolower((string) ($a->type === Article::TYPE_VIDEO ? $a->video_category : $a->section?->name)),
                'author'  => mb_strtolower((string) $a->author?->name),
                'summary' => mb_strtolower((string) $a->excerpt),
                'body'    => mb_strtolower($this->plain($a->content)),
            ];

            $scored = $this->score($terms, $phrase, $minTerms, $fields);
            if (!$scored) return null;

            return $scored + [
                'kind'     => $a->type === Article::TYPE_VIDEO ? 'video' : 'article',
                'model'    => $a,
                'category' => $a->type === Article::TYPE_VIDEO ? null : $a->section?->name,
                'date'     => $a->published_at ?? $a->created_at,
                'snippet'  => $this->snippet($a, $terms),
            ];
        })->filter()->values();
    }

    /** Gallery photos: matched on their title and the artist's name. */
    private function rankPhotos(array $terms, string $phrase): Collection
    {
        $needles = $this->needles($terms);

        $photos = GalleryPhoto::with('artist:id,name')
            ->where(function ($q) use ($needles) {
                foreach ($needles as $n) {
                    $q->orWhereLike('title', $this->like($n))
                      ->orWhereHas('artist', fn ($u) => $u->whereLike('name', $this->like($n)));
                }
            })
            ->latest()->limit(100)->get();

        $phrase   = mb_strtolower(trim($phrase));
        $minTerms = count($terms) >= 4 ? 2 : 1;

        return $photos->map(function (GalleryPhoto $p) use ($terms, $phrase, $minTerms) {
            $fields = [
                'title'   => mb_strtolower((string) $p->title),
                'section' => '',
                'author'  => mb_strtolower((string) $p->artist?->name),
                'summary' => '',
                'body'    => '',
            ];

            $scored = $this->score($terms, $phrase, $minTerms, $fields);
            if (!$scored) return null;

            return $scored + [
                'kind'     => 'photo',
                'model'    => $p,
                'category' => null,
                'date'     => $p->created_at,
                'snippet'  => $p->artist ? 'Photo by ' . $p->artist->name : '',
            ];
        })->filter()->values();
    }

    /** @return ?array{score: float, matched_in: array} null when the item doesn't match enough of the query */
    private function score(array $terms, string $phrase, int $minTerms, array $fields): ?array
    {
        $score = 0.0;
        $matchedTerms = 0;
        $matchedIn = [];
        foreach ($terms as $term) {
            [$s, $where] = $this->scoreWord($this->stem($term), $fields);
            foreach ($this->related($term) as $alt) {
                [$altScore, $altWhere] = $this->scoreWord($this->stem($alt), $fields);
                $s += $altScore * 0.4;
                $where = array_merge($where, $altWhere);
            }
            if ($s > 0) $matchedTerms++;
            $score += $s;
            $matchedIn = array_merge($matchedIn, $where);
        }
        if ($matchedTerms < $minTerms) return null;

        // Items that cover more of the query rank higher; an exact phrase is a strong signal
        $score *= 0.5 + 0.5 * ($matchedTerms / count($terms));
        if (mb_strlen($phrase) > 3 && count($terms) > 1) {
            if (str_contains($fields['title'], $phrase)) $score += 15;
            elseif (str_contains($fields['body'], $phrase)) $score += 6;
        }

        return ['score' => $score, 'matched_in' => array_values(array_unique($matchedIn))];
    }

    /** @return array{0: float, 1: string[]} the weighted score of one stem across the fields, and which fields matched */
    private function scoreWord(string $stem, array $fields): array
    {
        $prefix = '/(?<![\p{L}\p{N}])' . preg_quote($stem, '/') . '/u';
        $score = 0.0;
        $where = [];

        if (preg_match($prefix, $fields['title'])) {
            $score += preg_match('/(?<![\p{L}\p{N}])' . preg_quote($stem, '/') . '(?![\p{L}\p{N}])/u', $fields['title']) ? 18 : 12;
            $where[] = 'Title';
        }
        if (preg_match($prefix, $fields['section'])) { $score += 8; $where[] = 'Category'; }
        if (preg_match($prefix, $fields['author']))  { $score += 8; $where[] = 'Author'; }
        if (preg_match($prefix, $fields['summary'])) { $score += 5; $where[] = 'Summary'; }

        $count = preg_match_all($prefix, $fields['body']);
        if ($count) {
            $score += min($count, 6) * 1.5;
            $where[] = 'Article text';
        }

        return [$score, $where];
    }

    // ── Database pre-filter ────────────────────────────────────────────────────

    private function like(string $needle): string
    {
        return '%' . addcslashes($needle, '\\%_') . '%';
    }

    /**
     * Adds the title / summary / body conditions. Words the full-text index can handle use it (fast on a
     * big archive); very short words and ones the index ignores fall back to LIKE.
     */
    private function matchText($q, array $needles): void
    {
        $indexed = [];
        $plain = [];
        foreach ($needles as $n) {
            if ($this->fulltextUsable($n)) $indexed[] = $n; else $plain[] = $n;
        }

        if ($indexed) {
            // Only letters and digits reach here, so nothing in it can act as a search operator
            $q->orWhereRaw('MATCH(title, excerpt, content) AGAINST (? IN BOOLEAN MODE)', [implode(' ', array_map(fn ($n) => $n . '*', $indexed))]);
        }
        foreach ($plain as $n) {
            $q->orWhereLike('title', $this->like($n))
              ->orWhereLike('excerpt', $this->like($n))
              ->orWhereLike('content', $this->like($n));
        }
    }

    private function fulltextUsable(string $word): bool
    {
        if (mb_strlen($word) < 3 || in_array($word, self::FULLTEXT_IGNORED, true) || !preg_match('/^[\p{L}\p{N}]+$/u', $word)) {
            return false;
        }

        return self::$hasFulltext ??= (in_array(DB::getDriverName(), ['mysql', 'mariadb'], true)
            && Schema::hasIndex('articles', self::FULLTEXT_INDEX));
    }

    /** A short passage from the article around the first hit, so the reader can see why it matched. */
    private function snippet(Article $a, array $terms): string
    {
        $plain = $this->plain($a->content);

        foreach ($terms as $term) {
            foreach ([$term, ...$this->related($term)] as $word) {
                $stem = $this->stem($word);
                if (preg_match('/(?<![\p{L}\p{N}])' . preg_quote($stem, '/') . '/iu', $plain, $m, PREG_OFFSET_CAPTURE)) {
                    $pos   = mb_strlen(substr($plain, 0, $m[0][1])); // byte offset -> character offset
                    $start = max(0, $pos - 60);
                    $text  = mb_substr($plain, $start, 170);

                    return ($start > 0 ? '…' : '') . trim($text) . (mb_strlen($plain) > $start + 170 ? '…' : '');
                }
            }
        }

        return Str::limit($a->excerpt ?: $plain, 160);
    }

    private function plain(?string $html): string
    {
        return trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags(str_replace(['</p>', '<br>', '<br/>', '</div>'], ' ', $html ?? '')))));
    }
}
