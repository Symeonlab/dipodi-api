<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// --- THIS IS THE FIX ---
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// --- END OF FIX ---

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'sub_category', 'video_url', 'description', 'met_value',
    ];

    /**
     * The users that have favorited this exercise.
     */
    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favorite_exercises');
    }
}
