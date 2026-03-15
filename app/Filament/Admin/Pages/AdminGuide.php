<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Resources\ExerciseResource;
use App\Filament\Admin\Resources\NutritionAdviceResource;
use App\Filament\Admin\Resources\PlayerProfileResource;
use Filament\Pages\Page;

class AdminGuide extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?int $navigationSort = 100;
    protected static ?string $slug = 'admin-guide';

    protected static string $view = 'filament.admin.pages.admin-guide';

    public static function getNavigationLabel(): string
    {
        return __('admin.nav.admin_guide');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.help_documentation');
    }

    public function getTitle(): string
    {
        return __('admin.guide.title');
    }

    public function getViewData(): array
    {
        return [
            'sections' => $this->getGuideSections(),
            'quickLinks' => $this->getQuickLinks(),
        ];
    }

    private function getQuickLinks(): array
    {
        return [
            [
                'label' => __('admin.guide.add_exercise'),
                'url' => ExerciseResource::getUrl('create'),
                'icon' => 'heroicon-o-plus-circle',
                'color' => 'primary',
            ],
            [
                'label' => __('admin.guide.add_nutrition'),
                'url' => NutritionAdviceResource::getUrl('create'),
                'icon' => 'heroicon-o-plus-circle',
                'color' => 'success',
            ],
            [
                'label' => __('admin.guide.add_profile'),
                'url' => PlayerProfileResource::getUrl('create'),
                'icon' => 'heroicon-o-plus-circle',
                'color' => 'purple',
            ],
            [
                'label' => __('admin.guide.view_exercises'),
                'url' => ExerciseResource::getUrl('index'),
                'icon' => 'heroicon-o-list-bullet',
                'color' => 'gray',
            ],
        ];
    }

    private function getGuideSections(): array
    {
        return [
            [
                'title' => __('admin.sections.exercises.title'),
                'icon' => 'heroicon-o-play-circle',
                'color' => 'primary',
                'steps' => [
                    __('admin.sections.exercises.step1'),
                    __('admin.sections.exercises.step2'),
                    __('admin.sections.exercises.step3'),
                    __('admin.sections.exercises.step4'),
                    __('admin.sections.exercises.step5'),
                    __('admin.sections.exercises.step6'),
                    __('admin.sections.exercises.step7'),
                    __('admin.sections.exercises.step8'),
                    __('admin.sections.exercises.step9'),
                ],
                'tips' => [
                    __('admin.sections.exercises.tip1'),
                    __('admin.sections.exercises.tip2'),
                    __('admin.sections.exercises.tip3'),
                ],
            ],
            [
                'title' => __('admin.sections.nutrition.title'),
                'icon' => 'heroicon-o-light-bulb',
                'color' => 'success',
                'steps' => [
                    __('admin.sections.nutrition.step1'),
                    __('admin.sections.nutrition.step2'),
                    __('admin.sections.nutrition.step3'),
                    __('admin.sections.nutrition.step4'),
                    __('admin.sections.nutrition.step5'),
                    __('admin.sections.nutrition.step6'),
                    __('admin.sections.nutrition.step7'),
                    __('admin.sections.nutrition.step8'),
                    __('admin.sections.nutrition.step9'),
                    __('admin.sections.nutrition.step10'),
                ],
                'tips' => [
                    __('admin.sections.nutrition.tip1'),
                    __('admin.sections.nutrition.tip2'),
                    __('admin.sections.nutrition.tip3'),
                ],
            ],
            [
                'title' => __('admin.sections.profiles.title'),
                'icon' => 'heroicon-o-user-group',
                'color' => 'purple',
                'steps' => [
                    __('admin.sections.profiles.step1'),
                    __('admin.sections.profiles.step2'),
                    __('admin.sections.profiles.step3'),
                    __('admin.sections.profiles.step4'),
                    __('admin.sections.profiles.step5'),
                    __('admin.sections.profiles.step6'),
                    __('admin.sections.profiles.step7'),
                    __('admin.sections.profiles.step8'),
                ],
                'tips' => [
                    __('admin.sections.profiles.tip1'),
                    __('admin.sections.profiles.tip2'),
                    __('admin.sections.profiles.tip3'),
                ],
            ],
            [
                'title' => __('admin.sections.import.title'),
                'icon' => 'heroicon-o-arrow-up-tray',
                'color' => 'warning',
                'steps' => [
                    __('admin.sections.import.step1'),
                    __('admin.sections.import.step2'),
                    '```php',
                    "[\'name\' => \'Exercise Name\', \'category\' => \'MUSCULATION\', \'sub_category\' => \'BRAS\', \'video_url\' => \'https://youtube.com/shorts/xxx\', \'met_value\' => 5.5],",
                    '```',
                    __('admin.sections.import.step3'),
                    '```bash',
                    'docker exec dipodi-api-laravel.test-1 php artisan db:seed --class=DipoddiProgrammeSeeder --force',
                    '```',
                    __('admin.sections.import.step4'),
                ],
                'tips' => [
                    __('admin.sections.import.tip1'),
                    __('admin.sections.import.tip2'),
                    __('admin.sections.import.tip3'),
                ],
            ],
            [
                'title' => __('admin.sections.themes.title'),
                'icon' => 'heroicon-o-sparkles',
                'color' => 'info',
                'steps' => [
                    __('admin.sections.themes.step1'),
                    __('admin.sections.themes.step2'),
                    __('admin.sections.themes.step3'),
                    __('admin.sections.themes.step4'),
                    __('admin.sections.themes.step5'),
                    __('admin.sections.themes.step6'),
                    __('admin.sections.themes.step7'),
                    __('admin.sections.themes.step8'),
                ],
                'tips' => [
                    __('admin.sections.themes.tip1'),
                    __('admin.sections.themes.tip2'),
                ],
            ],
            [
                'title' => __('admin.sections.reference.title'),
                'icon' => 'heroicon-o-tag',
                'color' => 'gray',
                'steps' => [
                    __('admin.sections.reference.exercise_categories'),
                    __('admin.sections.reference.cat_musculation'),
                    __('admin.sections.reference.cat_bonus'),
                    __('admin.sections.reference.cat_maison'),
                    __('admin.sections.reference.cat_kine_renforcement'),
                    __('admin.sections.reference.cat_kine_mobilite'),
                    __('admin.sections.reference.cat_cardio'),
                    '',
                    __('admin.sections.reference.player_groups'),
                    __('admin.sections.reference.group_football'),
                    __('admin.sections.reference.group_fitness'),
                    __('admin.sections.reference.group_other'),
                ],
                'tips' => [
                    __('admin.sections.reference.tip1'),
                    __('admin.sections.reference.tip2'),
                ],
            ],
        ];
    }
}
