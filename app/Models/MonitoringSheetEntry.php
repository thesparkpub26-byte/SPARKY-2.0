<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringSheetEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'monitoring_sheet_id',
        'topic',
        'section',
        'article_type',
        'medium',
        'writer_assigned',
        'media_type',
        'artist_assigned',
        'interview_completed',
        'has_files',
        'current_status',
        'description',
        'priority',
        'deadline',
        'deadline_time',
        'article_headline',
        'article_author',
        'article_content',
    ];

    protected function casts(): array
    {
        return [
            'interview_completed' => 'boolean',
            'has_files' => 'boolean',
            'deadline' => 'date',
        ];
    }

    public function monitoringSheet()
    {
        return $this->belongsTo(MonitoringSheet::class);
    }
}
