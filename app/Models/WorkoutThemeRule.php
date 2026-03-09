<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutThemeRule extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     * This table holds static data.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workout_theme_id',
        'exercise_count',
        'sets',
        'reps',
        'recovery_time',
        'load_type',
        'mets',
        'duration',
        'charges',
        'speed_intensity',
        'sleep_requirement',
        'hydration',
        'freshness_24h',
        'freshness_48h',
        'freshness_72h',
        'rpe',
        'load_ua',
        'impact',
        'daily_alert_threshold',
        'weekly_alert_threshold',
        'elastic_recoil',
        'cfa',
        'supercomp_window',
        'gain_prediction',
        'injury_risk',
        'target_profiles',
    ];

    protected $casts = [
        'mets' => 'decimal:1',
        'freshness_24h' => 'decimal:2',
        'freshness_48h' => 'decimal:2',
        'freshness_72h' => 'decimal:2',
        'rpe' => 'integer',
        'load_ua' => 'integer',
        'impact' => 'integer',
        'target_profiles' => 'array',
    ];

    /**
     * Get the theme that owns these rules.
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(WorkoutTheme::class);
    }
}
