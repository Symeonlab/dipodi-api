<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HomeWorkoutRule extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'player_profile_id', 'objective',
        'duration', 'exercise_count', 'circuits',
        'effort_time', 'rest_time', 'recovery_time',
        'sort_order',
    ];

    public function playerProfile(): BelongsTo
    {
        return $this->belongsTo(PlayerProfile::class);
    }
}
