<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropheticRemedy extends Model
{
    protected $fillable = [
        'condition_key',
        'condition_name_fr', 'condition_name_en', 'condition_name_ar',
        'element_name_fr', 'element_name_en', 'element_name_ar',
        'mechanism_fr', 'mechanism_en', 'mechanism_ar',
        'recipe_fr', 'recipe_en', 'recipe_ar',
        'notes', 'sort_order',
    ];

    public function getConditionNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"condition_name_{$locale}"} ?? $this->condition_name_fr;
    }

    public function getElementNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"element_name_{$locale}"} ?? $this->element_name_fr;
    }

    public function getMechanismAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"mechanism_{$locale}"} ?? $this->mechanism_fr;
    }

    public function getRecipeAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"recipe_{$locale}"} ?? $this->recipe_fr;
    }

    public function scopeForCondition($query, string $conditionKey)
    {
        return $query->where('condition_key', $conditionKey);
    }
}
