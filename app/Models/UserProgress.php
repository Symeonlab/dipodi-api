<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    // This table uses the 'user_progress' table
    protected $table = 'user_progress';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'weight',
        'waist', // <-- Correct
        'chest', // <-- Correct
        'hips',  // <-- Correct
        'mood',
        'notes',
        'workout_completed',
    ];

    /**
     * Get the user that owns this progress log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
