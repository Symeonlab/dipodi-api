<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthAssessmentCategory extends Model
{
    protected $fillable = [
        'key',
        'name_fr',
        'name_en',
        'name_ar',
        'icon',
        'discipline',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function questions(): HasMany
    {
        return $this->hasMany(HealthAssessmentQuestion::class, 'category_id');
    }

    public function activeQuestions(): HasMany
    {
        return $this->questions()->where('is_active', true)->orderBy('sort_order');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDiscipline($query, ?string $discipline)
    {
        return $query->where(function ($q) use ($discipline) {
            $q->whereNull('discipline')
                ->orWhere('discipline', $discipline);
        });
    }

    // Accessors — resolve locale automatically via app()->getLocale()
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_{$locale}"} ?? $this->name_en;
    }

    // Legacy helper — kept for backward compatibility
    public function getLocalizedName(string $locale = 'en'): string
    {
        return match ($locale) {
            'fr' => $this->name_fr,
            'ar' => $this->name_ar ?? $this->name_en,
            default => $this->name_en,
        };
    }
}
