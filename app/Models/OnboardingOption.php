<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnboardingOption extends Model
{
    use HasFactory;
    public $timestamps = false; // This is static data, no timestamps needed
    protected $fillable = ['type', 'key', 'name_en', 'name_fr', 'name_ar'];
}
