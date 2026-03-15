<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class FeedbackSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'session_uuid',
        'total_questions',
        'answered_questions',
        'average_score',
        'status',
        'insights',
        'completed_at',
    ];

    protected $casts = [
        'total_questions' => 'integer',
        'answered_questions' => 'integer',
        'average_score' => 'decimal:2',
        'insights' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($session) {
            if (empty($session->session_uuid)) {
                $session->session_uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the user this session belongs to.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category for this session.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(FeedbackCategory::class, 'category_id');
    }

    /**
     * Get answers in this session.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(FeedbackAnswer::class, 'session_id');
    }

    /**
     * Scope to get completed sessions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get in-progress sessions.
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Calculate and update the average score from answers.
     */
    public function calculateAverageScore(): ?float
    {
        $scaleAnswers = $this->answers()
            ->whereHas('question', fn($q) => $q->where('answer_type', 'scale'))
            ->pluck('answer_value')
            ->filter(fn($v) => is_numeric($v))
            ->map(fn($v) => (float) $v);

        if ($scaleAnswers->isEmpty()) {
            return null;
        }

        return round($scaleAnswers->average(), 2);
    }

    /**
     * Mark session as completed.
     */
    public function markCompleted(): void
    {
        $this->average_score = $this->calculateAverageScore();
        $this->answered_questions = $this->answers()->count();
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Generate insights based on answers.
     */
    public function generateInsights(): array
    {
        $insights = [];
        $avgScore = $this->average_score;

        if ($avgScore !== null) {
            if ($avgScore >= 8) {
                $insights[] = __('feedback.insight.excellent_performance');
            } elseif ($avgScore >= 6) {
                $insights[] = __('feedback.insight.good_progress');
            } elseif ($avgScore >= 4) {
                $insights[] = __('feedback.insight.room_for_improvement');
            } else {
                $insights[] = __('feedback.insight.needs_attention');
            }
        }

        // Check for any "no" answers that might indicate issues
        $negativeAnswers = $this->answers()
            ->whereHas('question', fn($q) => $q->where('answer_type', 'yes_no'))
            ->where('answer_value', 'no')
            ->count();

        if ($negativeAnswers > 0) {
            $insights[] = __('feedback.insight.some_challenges_noted');
        }

        return $insights;
    }

    /**
     * Format for API response.
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'category' => $this->category?->key,
            'total_questions' => $this->total_questions,
            'answered_questions' => $this->answered_questions,
            'average_score' => $this->average_score,
            'status' => $this->status,
            'insights' => $this->insights ?? [],
            'completed_at' => $this->completed_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
