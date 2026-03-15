<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutFeedback extends Model
{
    use HasFactory;

    protected $table = 'workout_feedbacks';

    protected $fillable = [
        'user_id',
        'session_day',
        'session_theme',
        'exercises_completed',
        'elapsed_seconds',
        'difficulty_rating',
        'energy_level',
        'enjoyment_rating',
        'muscle_soreness',
        'sore_areas',
        'completed_all_sets',
        'skipped_reason',
        'notes',
        'preferred_adjustment',
    ];

    protected $casts = [
        'sore_areas' => 'array',
        'completed_all_sets' => 'boolean',
        'difficulty_rating' => 'integer',
        'energy_level' => 'integer',
        'enjoyment_rating' => 'integer',
        'muscle_soreness' => 'integer',
        'exercises_completed' => 'integer',
        'elapsed_seconds' => 'integer',
    ];

    // MARK: - Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // MARK: - Scopes

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForTheme($query, string $theme)
    {
        return $query->where('session_theme', $theme);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // MARK: - Helpers

    /**
     * Get the average difficulty rating for a user's recent workouts of a specific theme.
     */
    public static function averageDifficultyForTheme(int $userId, string $theme, int $days = 30): ?float
    {
        return static::forUser($userId)
            ->forTheme($theme)
            ->recent($days)
            ->avg('difficulty_rating');
    }

    /**
     * Generate workout adjustment recommendation based on recent feedback.
     */
    public static function getRecommendation(int $userId, string $theme): ?string
    {
        $recentFeedback = static::forUser($userId)
            ->forTheme($theme)
            ->recent(14)
            ->orderByDesc('created_at')
            ->limit(3)
            ->get();

        if ($recentFeedback->isEmpty()) {
            return null;
        }

        $avgDifficulty = $recentFeedback->avg('difficulty_rating');
        $avgEnergy = $recentFeedback->avg('energy_level');
        $avgEnjoyment = $recentFeedback->avg('enjoyment_rating');

        // Too hard: lower difficulty, more rest
        if ($avgDifficulty >= 4.5) {
            return 'decrease_intensity';
        }

        // Too easy: increase challenge
        if ($avgDifficulty <= 1.5) {
            return 'increase_intensity';
        }

        // Low energy + high difficulty: needs rest
        if ($avgEnergy <= 2 && $avgDifficulty >= 3.5) {
            return 'more_rest';
        }

        // Low enjoyment: vary the exercises
        if ($avgEnjoyment <= 2) {
            return 'more_variety';
        }

        // Explicit preference from last feedback
        $lastPreference = $recentFeedback->first()?->preferred_adjustment;
        if ($lastPreference && $lastPreference !== 'keep_same') {
            return $lastPreference;
        }

        return 'keep_same';
    }

    /**
     * Convert to API response array.
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'session_day' => $this->session_day,
            'session_theme' => $this->session_theme,
            'exercises_completed' => $this->exercises_completed,
            'elapsed_seconds' => $this->elapsed_seconds,
            'difficulty_rating' => $this->difficulty_rating,
            'energy_level' => $this->energy_level,
            'enjoyment_rating' => $this->enjoyment_rating,
            'muscle_soreness' => $this->muscle_soreness,
            'sore_areas' => $this->sore_areas,
            'completed_all_sets' => $this->completed_all_sets,
            'skipped_reason' => $this->skipped_reason,
            'notes' => $this->notes,
            'preferred_adjustment' => $this->preferred_adjustment,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
