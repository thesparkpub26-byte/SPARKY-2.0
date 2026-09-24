<?php

namespace App\Models;

use App\Support\PublicCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PublishedIssue extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'pdf_path', 'uploaded_by'];

    protected $appends = ['pdf_url'];

    // Readers' cached issue lists are rebuilt whenever an issue is added, edited or removed
    protected static function booted(): void
    {
        static::saved(fn () => PublicCache::forget('issues'));
        static::deleted(fn () => PublicCache::forget('issues'));
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }
        return Storage::disk('public')->url($this->pdf_path);
    }
}
