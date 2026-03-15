<?php

namespace App\Filament\Admin\Widgets;

use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static string $view = 'filament.admin.widgets.quick-actions-widget';
    protected static ?int $sort = 0;
    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return __('filament.widgets.quick_actions');
    }

    public function getActions(): array
    {
        return [
            [
                'label' => __('filament.widgets.new_exercise'),
                'url' => route('filament.admin.resources.exercises.create'),
                'icon' => 'heroicon-o-play-circle',
                'color' => 'success',
                'description' => __('filament.widgets.add_workout_exercise'),
            ],
            [
                'label' => __('filament.widgets.new_food_item'),
                'url' => route('filament.admin.resources.food-items.create'),
                'icon' => 'heroicon-o-cake',
                'color' => 'warning',
                'description' => __('filament.widgets.add_meal_snack'),
            ],
            [
                'label' => __('filament.widgets.new_post'),
                'url' => route('filament.admin.resources.posts.create'),
                'icon' => 'heroicon-o-newspaper',
                'color' => 'info',
                'description' => __('filament.widgets.create_blog_post'),
            ],
            [
                'label' => __('filament.resources.nutrition_advice'),
                'url' => route('filament.admin.resources.nutrition-advices.create'),
                'icon' => 'heroicon-o-heart',
                'color' => 'danger',
                'description' => __('filament.widgets.add_health_tip'),
            ],
            [
                'label' => __('filament.widgets.all_users'),
                'url' => route('filament.admin.resources.users.index'),
                'icon' => 'heroicon-o-users',
                'color' => 'purple',
                'description' => __('filament.widgets.manage_users'),
            ],
            [
                'label' => __('filament.resources.bonus_rules'),
                'url' => route('filament.admin.resources.bonus-workout-rules.index'),
                'icon' => 'heroicon-o-fire',
                'color' => 'gray',
                'description' => __('filament.widgets.workout_rules'),
            ],
        ];
    }
}
