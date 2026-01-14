<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProfile extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_profiles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'discipline', 'position', 'in_club', 'match_day', 'training_days', 'training_focus', 'level',
        'has_injury', 'injury_location', 'training_location', 'gym_preferences', 'cardio_preferences',
        'outdoor_preferences', 'home_preferences', 'gender', 'height', 'weight', 'age', 'country',
        'region', 'pro_level', 'apple_id', 'ideal_weight', 'birth_date', 'activity_level', 'goal',
        'morphology', 'hormonal_issues', 'is_vegetarian', 'meals_per_day', 'breakfast_preferences',
        'bad_habits', 'snacking_habits', 'vegetable_consumption', 'fish_consumption', 'meat_consumption',
        'dairy_consumption', 'sugary_food_consumption', 'cereal_consumption', 'starchy_food_consumption',
        'sugary_drink_consumption', 'egg_consumption', 'fruit_consumption', 'takes_medication',
        'has_diabetes', 'family_history', 'medical_history', 'is_onboarding_complete',
    ];

    // --- THIS IS THE FIX ---
    /**
     * The attributes that should be cast.
     * This tells Laravel to convert these JSON strings into arrays.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'in_club' => 'boolean',
        'has_injury' => 'boolean',
        'is_vegetarian' => 'boolean',
        'takes_medication' => 'boolean',
        'has_diabetes' => 'boolean',
        'is_onboarding_complete' => 'boolean',

        'height' => 'double',
        'weight' => 'double',
        'ideal_weight' => 'double',
        'age' => 'integer',
        'birth_date' => 'date:Y-m-d',

        // JSON Array Fields
        'training_days' => 'array',
        'gym_preferences' => 'array',
        'cardio_preferences' => 'array',
        'outdoor_preferences' => 'array',
        'home_preferences' => 'array',
        'breakfast_preferences' => 'array',
        'bad_habits' => 'array',
        'family_history' => 'array',
        'medical_history' => 'array',
    ];
    // --- END OF FIX ---

    /**
     * Get the user that owns the profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
