<?php

namespace App\Models;

use App\Support\PublicCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image_path', 'uploaded_by', 'artist_id'];

    protected $appends = ['image_url'];

    // Readers' cached gallery lists are rebuilt whenever a photo is added, edited or removed
    protected static function booted(): void
    {
        static::saved(fn () => PublicCache::forget('gallery'));
        static::deleted(fn () => PublicCache::forget('gallery'));
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }
        return Storage::disk('public')->url($this->image_path);
    }
}
