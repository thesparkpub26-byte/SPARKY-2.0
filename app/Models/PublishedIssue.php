<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PublishedIssue extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'pdf_path', 'flipbook_url', 'uploaded_by'];

    protected $appends = ['pdf_url'];

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
