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
    ];

    /**
     * Get the theme that owns these rules.
     */
    public function theme(): BelongsTo
    {
        return $this->belongsTo(WorkoutTheme::class);
    }
}
