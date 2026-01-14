<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionAdvice extends Model
{
    use HasFactory;
    protected $fillable = [
        'condition_name', 'foods_to_avoid', 'foods_to_eat',
        'prophetic_advice_fr', 'prophetic_advice_en', 'prophetic_advice_ar',
    ];
    protected $casts = [
        'foods_to_avoid' => 'array',
        'foods_to_eat' => 'array',
    ];
}
