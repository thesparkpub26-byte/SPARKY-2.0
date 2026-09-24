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

    public function articles()
    {
        return $this->hasMany(Article::class);
    }
}
