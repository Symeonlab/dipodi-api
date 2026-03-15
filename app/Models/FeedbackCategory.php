<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedbackCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name_fr',
        'name_en',
        'name_ar',
        'icon',
        'discipline',
        'position',
        'goal',
        'requires_injury',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'requires_injury' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get questions for this category.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(FeedbackQuestion::class, 'category_id');
    }

    /**
     * Get active questions ordered by sort_order.
     */
    public function activeQuestions(): HasMany
    {
        return $this->questions()
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Get sessions for this category.
     */
    public function sessions(): HasMany
    {
        return $this->hasMany(FeedbackSession::class, 'category_id');
    }

    /**
     * Scope to get active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by discipline.
     */
    public function scopeForDiscipline($query, ?string $discipline)
    {
        return $query->where(function ($q) use ($discipline) {
            $q->whereNull('discipline')
              ->orWhere('discipline', $discipline);
        });
    }

    /**
     * Scope to filter by position (for football).
     */
    public function scopeForPosition($query, ?string $position)
    {
        return $query->where(function ($q) use ($position) {
            $q->whereNull('position')
              ->orWhere('position', $position);
        });
    }

    /**
     * Scope to filter by goal.
     */
    public function scopeForGoal($query, ?string $goal)
    {
        return $query->where(function ($q) use ($goal) {
            $q->whereNull('goal')
              ->orWhere('goal', $goal);
        });
    }

    /**
     * Scope to filter by injury status.
     */
    public function scopeForInjuryStatus($query, bool $hasInjury)
    {
        if (!$hasInjury) {
            return $query->where('requires_injury', false);
        }
        return $query;
    }

    // Accessor — resolve locale automatically via app()->getLocale()
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_{$locale}"} ?? $this->name_en;
    }

    /**
     * Get localized name based on locale (legacy helper).
     */
    public function getLocalizedName(string $locale = 'en'): string
    {
        return match ($locale) {
            'ar' => $this->name_ar ?? $this->name_fr,
            'en' => $this->name_en ?? $this->name_fr,
            default => $this->name_fr,
        };
    }

    /**
     * Get relevant categories for a user profile.
     */
    public static function getRelevantForUser(
        ?string $discipline,
        ?string $position,
        ?string $goal,
        bool $hasInjury = false
    ) {
        return static::active()
            ->forDiscipline($discipline)
            ->forPosition($position)
            ->forGoal($goal)
            ->forInjuryStatus($hasInjury)
            ->orderBy('sort_order')
            ->get();
    }
}
