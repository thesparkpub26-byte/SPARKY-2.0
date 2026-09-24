<?php

namespace App\Models;

use App\Support\PublicCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'color'];

    // A renamed or removed section changes the badges on cached article lists
    protected static function booted(): void
    {
        static::saved(fn () => PublicCache::forget('articles'));
        static::deleted(fn () => PublicCache::forget('articles'));
    }

    /**
     * The section a name refers to ("Literary", "Sci&Tech"...), or null when there is none. The table still has
     * older duplicate rows (two "News"), so the first one is used, the same as the dropdowns in the app.
     */
    public static function idForName(?string $name): ?int
    {
        $key = strtolower(trim((string) $name));
        $key = ['sci&tech' => 'sci-tech', 'features' => 'feature'][$key] ?? $key;

        return $key === '' ? null : static::whereRaw('LOWER(name) = ?', [$key])->orderBy('id')->value('id');
    }

    /** The section named in a task's notes ("Section: Literary | Coverage: ..."), or null. */
    public static function idFromNotes(?string $notes): ?int
    {
        return preg_match('/(?:^|\|)\s*Section:\s*([^|]+)/i', (string) $notes, $m) ? static::idForName($m[1]) : null;
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
