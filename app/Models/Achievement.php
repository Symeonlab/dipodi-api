<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name_en',
        'name_fr',
        'name_ar',
        'description_en',
        'description_fr',
        'description_ar',
        'icon',
        'points',
        'category',
    ];

    protected $casts = [
        'points' => 'integer',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get localized name.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "name_{$locale}";
        return $this->{$field} ?? $this->name_en;
    }

    /**
     * Get localized description.
     */
    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        $field = "description_{$locale}";
        return $this->{$field} ?? $this->description_en;
    }
}
