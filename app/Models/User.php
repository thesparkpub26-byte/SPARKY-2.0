<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'program',
        'year_section',
        'avatar',
        'bio',
        'is_active',
        'profile_picture',
    ];

    protected $appends = ['profile_picture_url'];

    protected $hidden = ['password'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Role constants
    const ROLE_ADMIN          = 'admin';
    const ROLE_EIC            = 'eic';
    const ROLE_SECTION_EDITOR = 'section_editor';
    const ROLE_STAFF_WRITER   = 'staff_writer';
    const ROLE_STAFF_ARTIST   = 'staff_artist';
    const ROLE_READER         = 'reader';

    // Relationships
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
    public function isAdmin(): bool         { return $this->role === self::ROLE_ADMIN; }
    public function isEIC(): bool           { return $this->role === self::ROLE_EIC; }
    public function isSectionEditor(): bool { return $this->role === self::ROLE_SECTION_EDITOR; }
    public function isStaffWriter(): bool   { return $this->role === self::ROLE_STAFF_WRITER; }
    public function isStaffArtist(): bool   { return $this->role === self::ROLE_STAFF_ARTIST; }
    public function isReader(): bool        { return $this->role === self::ROLE_READER; }

    public function isStaff(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN, self::ROLE_EIC,
            self::ROLE_SECTION_EDITOR, self::ROLE_STAFF_WRITER, self::ROLE_STAFF_ARTIST,
        ]);
    }

    /**
     * Full public URL for the profile picture.
     * Returns null if no picture has been set.
     */
    public function getProfilePictureUrlAttribute(): ?string
    {
        if (!$this->profile_picture) {
            return null;
        }
        return Storage::disk('public')->url($this->profile_picture);
    }
}
