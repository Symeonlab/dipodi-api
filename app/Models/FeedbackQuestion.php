<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeedbackQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
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

    /**
     * Get the category this question belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FeedbackCategory::class, 'category_id');
    }

    /**
     * Get answers for this question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(FeedbackAnswer::class, 'question_id');
    }

    /**
     * Scope to get active questions.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to order by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Accessor — resolve locale automatically via app()->getLocale()
    public function getQuestionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"question_{$locale}"} ?? $this->question_en;
    }

    /**
     * Get localized question based on locale (legacy helper).
     */
    public function getLocalizedQuestion(string $locale = 'en'): string
    {
        return match ($locale) {
            'ar' => $this->question_ar ?? $this->question_fr,
            'en' => $this->question_en ?? $this->question_fr,
            default => $this->question_fr,
        };
    }

    /**
     * Format for API response.
     */
    public function toApiArray(string $locale = 'en'): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category?->key,
            'question_fr' => $this->question_fr,
            'question_en' => $this->question_en,
            'question_ar' => $this->question_ar,
            'answer_type' => $this->answer_type,
            'answer_options' => $this->answer_options,
            'sort_order' => $this->sort_order,
        ];
    }
}
