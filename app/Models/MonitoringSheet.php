<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringSheet extends Model
{
    use HasFactory;

    protected $fillable = ['press_work_id', 'publication_type', 'title', 'status'];

    public function pressWork()
    {
        return $this->belongsTo(PressWork::class);
    }
}
