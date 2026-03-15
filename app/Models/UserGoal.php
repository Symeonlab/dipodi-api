<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'goal_type',
        'target_weight',
        'target_waist',
        'target_chest',
        'target_hips',
        'target_workouts_per_week',
        'start_weight',
        'start_waist',
        'start_chest',
        'start_hips',
        'current_progress_percentage',
        'weeks_completed',
        'total_weeks',
        'start_date',
        'target_date',
        'status',
        'completed_at',
        'achievements',
        'notes',
    ];

    protected $casts = [
        'target_weight' => 'decimal:2',
        'target_waist' => 'decimal:2',
        'target_chest' => 'decimal:2',
        'target_hips' => 'decimal:2',
        'start_weight' => 'decimal:2',
        'start_waist' => 'decimal:2',
        'start_chest' => 'decimal:2',
        'start_hips' => 'decimal:2',
        'current_progress_percentage' => 'decimal:2',
        'start_date' => 'date',
        'target_date' => 'date',
        'completed_at' => 'datetime',
        'achievements' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate progress based on current vs target metrics.
     */
    public function calculateProgress(): float
    {
        $user = $this->user;
        $latestProgress = $user->progressLogs()->latest('date')->first();

        if (!$latestProgress) {
            return 0;
        }

        $progressMetrics = [];

        // Weight progress
        if ($this->target_weight && $this->start_weight) {
            $weightDiff = abs($this->start_weight - $this->target_weight);
            $currentDiff = abs($this->start_weight - ($latestProgress->weight ?? $this->start_weight));
            if ($weightDiff > 0) {
                $progressMetrics[] = min(100, ($currentDiff / $weightDiff) * 100);
            }
        }

        // Waist progress
        if ($this->target_waist && $this->start_waist) {
            $waistDiff = abs($this->start_waist - $this->target_waist);
            $currentDiff = abs($this->start_waist - ($latestProgress->waist ?? $this->start_waist));
            if ($waistDiff > 0) {
                $progressMetrics[] = min(100, ($currentDiff / $waistDiff) * 100);
            }
        }

        // Calculate average progress
        if (empty($progressMetrics)) {
            // If no body metrics, use time-based progress
            $totalDays = $this->start_date->diffInDays($this->target_date);
            $daysElapsed = $this->start_date->diffInDays(now());
            return $totalDays > 0 ? min(100, ($daysElapsed / $totalDays) * 100) : 0;
        }

        return array_sum($progressMetrics) / count($progressMetrics);
    }

    /**
     * Get goal type label.
     */
    public function getGoalTypeLabelAttribute(): string
    {
        return match ($this->goal_type) {
            'weight_loss' => __('Weight Loss'),
            'muscle_gain' => __('Muscle Gain'),
            'maintain' => __('Maintain Shape'),
            'custom' => __('Custom Goal'),
            default => $this->goal_type,
        };
    }

    /**
     * Check if goal is on track.
     */
    public function isOnTrack(): bool
    {
        $expectedProgress = $this->getExpectedProgress();
        return $this->current_progress_percentage >= ($expectedProgress - 10);
    }

    /**
     * Get expected progress based on time elapsed.
     */
    public function getExpectedProgress(): float
    {
        $totalDays = $this->start_date->diffInDays($this->target_date);
        $daysElapsed = $this->start_date->diffInDays(now());
        return $totalDays > 0 ? min(100, ($daysElapsed / $totalDays) * 100) : 0;
    }

    /**
     * Check and award achievements.
     */
    public function checkAchievements(): array
    {
        $newAchievements = [];
        $user = $this->user;

        // First workout achievement
        if ($user->progressLogs()->where('workout_completed', '!=', null)->count() === 1) {
            $newAchievements[] = 'first_workout';
        }

        // Week streak achievements
        $consecutiveWeeks = $this->calculateConsecutiveWeeks();
        if ($consecutiveWeeks >= 4 && !$this->hasAchievement('week_streak_4')) {
            $newAchievements[] = 'week_streak_4';
        }
        if ($consecutiveWeeks >= 8 && !$this->hasAchievement('week_streak_8')) {
            $newAchievements[] = 'week_streak_8';
        }
        if ($consecutiveWeeks >= 12 && !$this->hasAchievement('week_streak_12')) {
            $newAchievements[] = 'week_streak_12';
        }

        // Progress milestones
        if ($this->current_progress_percentage >= 25 && !$this->hasAchievement('progress_25')) {
            $newAchievements[] = 'progress_25';
        }
        if ($this->current_progress_percentage >= 50 && !$this->hasAchievement('progress_50')) {
            $newAchievements[] = 'progress_50';
        }
        if ($this->current_progress_percentage >= 75 && !$this->hasAchievement('progress_75')) {
            $newAchievements[] = 'progress_75';
        }
        if ($this->current_progress_percentage >= 100 && !$this->hasAchievement('goal_complete')) {
            $newAchievements[] = 'goal_complete';
        }

        // Award new achievements
        foreach ($newAchievements as $achievementKey) {
            $this->awardAchievement($achievementKey);
        }

        return $newAchievements;
    }

    protected function hasAchievement(string $key): bool
    {
        return in_array($key, $this->achievements ?? []);
    }

    protected function awardAchievement(string $key): void
    {
        $achievements = $this->achievements ?? [];
        if (!in_array($key, $achievements)) {
            $achievements[] = $key;
            $this->update(['achievements' => $achievements]);

            // Also add to user_achievements pivot
            $achievement = Achievement::where('key', $key)->first();
            if ($achievement) {
                $this->user->achievements()->syncWithoutDetaching([
                    $achievement->id => ['earned_at' => now()]
                ]);
            }
        }
    }

    protected function calculateConsecutiveWeeks(): int
    {
        $workouts = $this->user->progressLogs()
            ->where('workout_completed', '!=', null)
            ->orderBy('date', 'desc')
            ->get();

        if ($workouts->isEmpty()) {
            return 0;
        }

        $consecutiveWeeks = 0;
        $currentWeek = now()->startOfWeek();

        while (true) {
            $weekStart = $currentWeek->copy()->subWeeks($consecutiveWeeks);
            $weekEnd = $weekStart->copy()->endOfWeek();

            $hasWorkout = $workouts->whereBetween('date', [$weekStart, $weekEnd])->isNotEmpty();

            if (!$hasWorkout) {
                break;
            }

            $consecutiveWeeks++;
        }

        return $consecutiveWeeks;
    }
}
