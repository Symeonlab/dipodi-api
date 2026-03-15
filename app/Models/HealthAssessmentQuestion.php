<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthAssessmentQuestion extends Model
{
    protected $fillable = [
        'category_id',
        'subcategory',
        'question_fr',
        'question_en',
        'question_ar',
        'answer_type',
        'answer_options',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'answer_options' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(HealthAssessmentCategory::class, 'category_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(HealthAssessmentAnswer::class, 'question_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors — resolve locale automatically via app()->getLocale()
    public function getQuestionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"question_{$locale}"} ?? $this->question_en;
    }

    // Legacy helper — kept for backward compatibility
    public function getLocalizedQuestion(string $locale = 'en'): string
    {
        return match ($locale) {
            'fr' => $this->question_fr,
            'ar' => $this->question_ar ?? $this->question_en,
            default => $this->question_en,
        };
    }
}
