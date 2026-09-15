<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PressWork extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'academic_year', 'created_by'];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function monitoringSheets()
    {
        return $this->hasMany(MonitoringSheet::class);
    }
}
