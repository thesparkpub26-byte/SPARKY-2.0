<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitoringSheet extends Model
{
    use HasFactory;

    protected $fillable = ['press_work_id', 'publication_type', 'title', 'status'];

    /** The academic year (press work) the sheet belongs to. */
    public function pressWork()
    {
        return $this->belongsTo(PressWork::class);
    }

    /** The entries (tasks) listed on the sheet. */
    public function entries()
    {
        return $this->hasMany(MonitoringSheetEntry::class);
    }
}
