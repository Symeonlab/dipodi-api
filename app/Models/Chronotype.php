<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chronotype extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'key', 'name_fr', 'name_en', 'name_ar',
        'wake_time', 'peak_start', 'peak_end', 'bedtime',
        'description_fr', 'description_en', 'description_ar',
        'character_fr', 'character_en', 'character_ar',
        'icon', 'sort_order',
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

    public function getCharacterAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"character_{$locale}"} ?? $this->character_fr;
    }
}
