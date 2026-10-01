<?php

namespace App\Models;

use App\Support\PublicCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class GalleryPhoto extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'image_path', 'uploaded_by', 'artist_id', 'published_at'];

    protected $appends = ['image_url'];

    protected $casts = ['published_at' => 'datetime'];

    // Readers' cached gallery lists are rebuilt whenever a photo is added, edited or removed
    protected static function booted(): void
    {
        static::saved(fn () => PublicCache::forget('gallery'));
        static::deleted(fn () => PublicCache::forget('gallery'));
    }

    /** The staff member who uploaded the photo. */
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /** The artist credited for the photo. */
    public function artist()
    {
        return $this->belongsTo(User::class, 'artist_id');
    }

    /** The public address of the photo's image, or null when it has none. */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }
        return Storage::disk('public')->url($this->image_path);
    }
}
