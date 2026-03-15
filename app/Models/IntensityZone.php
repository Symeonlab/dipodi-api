<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntensityZone extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'color', 'name_fr', 'name_en', 'name_ar',
        'intensity_range', 'description_fr', 'description_en', 'description_ar',
        'rpe_min', 'rpe_max', 'sort_order',
    ];

    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_{$locale}"} ?? $this->name_fr;
    }

    public function getDescriptionAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_fr;
    }
}
