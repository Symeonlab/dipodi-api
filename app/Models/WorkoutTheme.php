<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WorkoutTheme extends Model
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
        'name', 'type', 'discipline', 'zone_color',
        'quality_method', 'display_name', 'sort_order',
    ];

    /**
     * Get the rules associated with this workout theme.
     */
    public function rules(): HasOne
    {
        return $this->hasOne(WorkoutThemeRule::class);
    }

    /**
     * The player profiles that use this theme.
     */
    public function playerProfiles(): BelongsToMany
    {
        return $this->belongsToMany(PlayerProfile::class, 'player_profile_themes')
            ->withPivot('percentage');
    }
}
