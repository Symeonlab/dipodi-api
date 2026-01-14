<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the profile associated with the user.
     */
    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Boot the model to create a profile when a user is created.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            // This ensures every new user gets a profile
            if ($user->profile()->doesntExist()) {
                $user->profile()->create();
            }
        });
    }

    /**
     * This function tells Filament who is allowed to access the admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        // Only allow users with these roles to access http://localhost/admin
        return in_array($this->role, ['admin', 'coach', 'manager']);
    }

    /**
     * Get the reminder settings for the user.
     */
    public function reminderSettings(): HasOne
    {
        return $this->hasOne(UserReminderSetting::class);
    }

    /**
     * The exercises that this user has favorited.
     */
    public function favoriteExercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'user_favorite_exercises');
    }

    /**
     * Get the progress logs for the user.
     */
    public function progressLogs(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }
}
