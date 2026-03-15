<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class HealthAssessmentSession extends Model
{
    protected $fillable = [
        'user_id',
        'session_uuid',
        'status',
        'total_questions',
        'answered_questions',
        'health_insights',
        'recommendations',
        'completed_at',
    ];

    protected $casts = [
        'health_insights' => 'array',
        'recommendations' => 'array',
        'completed_at' => 'datetime',
        'total_questions' => 'integer',
        'answered_questions' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->session_uuid)) {
                $model->session_uuid = (string) Str::uuid();
            }
        });
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(HealthAssessmentAnswer::class, 'session_id');
    }

    // Scopes
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    // Methods
    public function markCompleted(): void
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->health_insights = $this->generateInsights();
        $this->recommendations = $this->generateRecommendations();
        $this->save();
    }

    public function generateInsights(): array
    {
        $insights = [];
        $answers = $this->answers()->with('question.category')->get();

        // Group answers by category
        $byCategory = $answers->groupBy(fn($a) => $a->question->category->key ?? 'other');

        foreach ($byCategory as $categoryKey => $categoryAnswers) {
            $yesCount = $categoryAnswers->filter(fn($a) =>
                strtolower($a->answer_value) === 'oui' ||
                strtolower($a->answer_value) === 'yes' ||
                $a->answer_value === '1'
            )->count();

            $total = $categoryAnswers->count();
            $percentage = $total > 0 ? round(($yesCount / $total) * 100) : 0;

            if ($percentage > 50) {
                $categoryName = $categoryAnswers->first()->question->category->name_fr ?? $categoryKey;
                $insights[] = "Attention particulière recommandée: {$categoryName} ({$percentage}% de réponses positives)";
            }
        }

        return $insights;
    }

    public function generateRecommendations(): array
    {
        $recommendations = [];
        $answers = $this->answers()->with('question')->get();

        // Check for specific patterns
        $fatigueAnswers = $answers->filter(fn($a) =>
            str_contains(strtolower($a->question->subcategory ?? ''), 'fatigue')
        );

        if ($fatigueAnswers->filter(fn($a) => strtolower($a->answer_value) === 'oui')->count() >= 3) {
            $recommendations[] = [
                'type' => 'nutrition',
                'priority' => 'high',
                'message_fr' => 'Augmenter la consommation de glucides complexes et de fer',
                'message_en' => 'Increase consumption of complex carbohydrates and iron',
            ];
        }

        // Check for dehydration
        $dehydrationAnswers = $answers->filter(fn($a) =>
            str_contains(strtolower($a->question->subcategory ?? ''), 'hydrat')
        );

        if ($dehydrationAnswers->filter(fn($a) => strtolower($a->answer_value) === 'oui')->count() >= 2) {
            $recommendations[] = [
                'type' => 'hydration',
                'priority' => 'high',
                'message_fr' => 'Augmenter l\'hydratation à 2-3 litres par jour minimum',
                'message_en' => 'Increase hydration to at least 2-3 liters per day',
            ];
        }

        // Check for muscle issues
        $muscleAnswers = $answers->filter(fn($a) =>
            str_contains(strtolower($a->question->subcategory ?? ''), 'muscle') ||
            str_contains(strtolower($a->question->subcategory ?? ''), 'crampe')
        );

        if ($muscleAnswers->filter(fn($a) => strtolower($a->answer_value) === 'oui')->count() >= 2) {
            $recommendations[] = [
                'type' => 'supplement',
                'priority' => 'medium',
                'message_fr' => 'Considérer un supplément en magnésium et potassium',
                'message_en' => 'Consider magnesium and potassium supplementation',
            ];
        }

        return $recommendations;
    }

    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'session_uuid' => $this->session_uuid,
            'status' => $this->status,
            'total_questions' => $this->total_questions,
            'answered_questions' => $this->answered_questions,
            'progress' => $this->total_questions > 0
                ? round(($this->answered_questions / $this->total_questions) * 100)
                : 0,
            'health_insights' => $this->health_insights ?? [],
            'recommendations' => $this->recommendations ?? [],
            'completed_at' => $this->completed_at?->toIso8601String(),
        ];
    }
}
