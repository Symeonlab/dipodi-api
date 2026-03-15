<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SleepProtocol extends Model
{
    protected $fillable = [
        'condition_key', 'condition_name_fr', 'condition_name_en', 'condition_name_ar',
        'cycles_min', 'cycles_max', 'total_sleep',
        'objective_fr', 'objective_en', 'objective_ar',
        'category', 'sort_order',
    ];

    protected $casts = [
        'cycles_min' => 'integer',
        'cycles_max' => 'integer',
    ];

    public function getConditionNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"condition_name_{$locale}"} ?? $this->condition_name_fr;
    }

    public function getObjectiveAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"objective_{$locale}"} ?? $this->objective_fr;
    }
}
