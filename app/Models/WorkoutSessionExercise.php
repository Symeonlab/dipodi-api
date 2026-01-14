<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutSessionExercise extends Model
{
    use HasFactory;

    // THE FIX: This table also doesn't have timestamps
    public $timestamps = false;

    protected $fillable = [
        'workout_session_id',
        'name',
        'sets',
        'reps',
        'recovery',
        'video_url',
    ];
}
