<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingDayLogic extends Model
{
    public $timestamps = false;

    protected $table = 'training_day_logic';

    protected $fillable = [
        'total_days', 'theme_principal_count', 'random_count',
        'alt_theme_count', 'alt_random_count',
    ];

    protected $casts = [
        'total_days' => 'integer',
        'theme_principal_count' => 'integer',
        'random_count' => 'integer',
        'alt_theme_count' => 'integer',
        'alt_random_count' => 'integer',
    ];
}
