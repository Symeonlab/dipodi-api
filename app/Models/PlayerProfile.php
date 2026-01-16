<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PlayerProfile extends Model
{
    use HasFactory;

    // THE FIX: This table also doesn't have timestamps
    public $timestamps = false;

    protected $fillable = ['name', 'group', 'description'];

    /**
     * The workout themes associated with this profile.
     */
    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(WorkoutTheme::class, 'player_profile_themes')
            ->withPivot('percentage');
    }
}
