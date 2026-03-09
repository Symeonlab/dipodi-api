<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'category', 'tags',
        'h_plus_1_energy', 'h_plus_24_recovery', 'meal_timing',
    ];

    protected $casts = [
        'tags' => 'array',
        'h_plus_1_energy' => 'decimal:1',
        'h_plus_24_recovery' => 'decimal:1',
    ];
}
