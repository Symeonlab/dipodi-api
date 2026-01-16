<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusWorkoutRule extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ['level', 'type', 'sets', 'reps', 'recovery', 'duration', 'exercise_count'];
}
