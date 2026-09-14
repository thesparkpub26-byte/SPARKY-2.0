<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'section_id',
        'avatar',
        'bio',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role constants
    const ROLE_ADMIN = 'admin';
    const ROLE_EIC = 'eic';
    const ROLE_SECTION_EDITOR = 'section_editor';
    const ROLE_STAFF_WRITER = 'staff_writer';
    const ROLE_STAFF_ARTIST = 'staff_artist';

    // Relationships
    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assignee_id');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'assigned_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Role helpers
    public function isAdmin(): bool { return $this->role === self::ROLE_ADMIN; }
    public function isEIC(): bool { return $this->role === self::ROLE_EIC; }
    public function isSectionEditor(): bool { return $this->role === self::ROLE_SECTION_EDITOR; }
    public function isStaffWriter(): bool { return $this->role === self::ROLE_STAFF_WRITER; }
    public function isStaffArtist(): bool { return $this->role === self::ROLE_STAFF_ARTIST; }
}
