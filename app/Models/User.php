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
        'secondary_role',
        'tertiary_role',
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

    protected static function booted()
    {
        static::deleting(function (User $user) {
            foreach ($user->articles as $article) {
                if ($article->cover_image) {
                    Storage::disk('public')->delete($article->cover_image);
                }
                $article->delete();
            }
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
        });
    }

    // Role constants
    const ROLE_ADMIN             = 'admin';
    const ROLE_EIC               = 'eic';
    const ROLE_SECTION_EDITOR    = 'section_editor';
    const ROLE_STAFF_WRITER      = 'staff_writer';
    const ROLE_STAFF_ARTIST      = 'staff_artist';
    const ROLE_STAFF_BROADCASTER = 'staff_broadcaster';
    const ROLE_READER            = 'reader';

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
    public function isAdmin(): bool            { return $this->role === self::ROLE_ADMIN; }
    public function isEIC(): bool              { return $this->role === self::ROLE_EIC; }
    public function isSectionEditor(): bool    { return $this->role === self::ROLE_SECTION_EDITOR; }
    public function isStaffWriter(): bool      { return $this->role === self::ROLE_STAFF_WRITER; }
    public function isStaffArtist(): bool      { return $this->role === self::ROLE_STAFF_ARTIST; }
    public function isStaffBroadcaster(): bool { return $this->role === self::ROLE_STAFF_BROADCASTER; }
    public function isReader(): bool           { return $this->role === self::ROLE_READER; }

    public function isStaff(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN, self::ROLE_EIC,
            self::ROLE_SECTION_EDITOR, self::ROLE_STAFF_WRITER, self::ROLE_STAFF_ARTIST,
            self::ROLE_STAFF_BROADCASTER,
        ]);
    }

    /**
     * The title shown for this person right now (e.g. "News Writer", "News Editor", "Photojournalist").
     * Work records copy this when they are created, so later promotions don't rewrite history.
     */
    public function displayRole(): string
    {
        return trim((string) $this->secondary_role) !== ''
            ? $this->secondary_role
            : ucwords(str_replace('_', ' ', (string) $this->role));
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
