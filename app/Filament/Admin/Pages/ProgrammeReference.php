<?php

namespace App\Filament\Admin\Pages;

use App\Models\Exercise;
use App\Models\IntensityZone;
use App\Models\PlayerProfile;
use App\Models\WorkoutTheme;
use Filament\Pages\Page;

class ProgrammeReference extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?int $navigationSort = 101;
    protected static ?string $slug = 'programme-reference';

    protected static string $view = 'filament.admin.pages.programme-reference';

    public static function getNavigationLabel(): string
    {
        return 'Programme Reference';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.nav.help_documentation');
    }

    public function getTitle(): string
    {
        return 'Programme Reference';
    }

    public function getViewData(): array
    {
        return [
            'zones' => $this->getZones(),
            'themeTypes' => $this->getThemeTypes(),
            'stats' => $this->getStats(),
            'algorithmSteps' => $this->getAlgorithmSteps(),
        ];
    }

    private function getZones(): array
    {
        return [
            [
                'color' => 'blue',
                'label' => 'Zone 1 - Recovery',
                'range' => '50-60%',
                'description' => 'Active recovery and regeneration. Light mobility work, stretching, and low-impact exercises to promote blood flow and muscle repair.',
                'bg_class' => 'bg-blue-500',
                'text_class' => 'text-blue-700 dark:text-blue-300',
                'bg_light' => 'bg-blue-50 dark:bg-blue-900/30',
            ],
            [
                'color' => 'green',
                'label' => 'Zone 2 - Endurance',
                'range' => '60-70%',
                'description' => 'Aerobic base building and muscular endurance. Moderate-intensity work that develops stamina and cardiovascular fitness.',
                'bg_class' => 'bg-green-500',
                'text_class' => 'text-green-700 dark:text-green-300',
                'bg_light' => 'bg-green-50 dark:bg-green-900/30',
            ],
            [
                'color' => 'yellow',
                'label' => 'Zone 3 - Match Rhythm',
                'range' => '70-80%',
                'description' => 'Tempo and threshold training. Simulates match-day intensity with sustained effort to build lactate tolerance and game fitness.',
                'bg_class' => 'bg-yellow-500',
                'text_class' => 'text-yellow-700 dark:text-yellow-300',
                'bg_light' => 'bg-yellow-50 dark:bg-yellow-900/30',
            ],
            [
                'color' => 'orange',
                'label' => 'Zone 4 - High Intensity',
                'range' => '80-90%',
                'description' => 'Power and strength development. Heavy loads, explosive movements, and high-effort intervals that push anaerobic capacity.',
                'bg_class' => 'bg-orange-500',
                'text_class' => 'text-orange-700 dark:text-orange-300',
                'bg_light' => 'bg-orange-50 dark:bg-orange-900/30',
            ],
            [
                'color' => 'red',
                'label' => 'Zone 5 - Maximum',
                'range' => '90-100%',
                'description' => 'Maximum effort and peak performance. Near-maximal or maximal loads for strength, speed, and power. Used sparingly to prevent overtraining.',
                'bg_class' => 'bg-red-500',
                'text_class' => 'text-red-700 dark:text-red-300',
                'bg_light' => 'bg-red-50 dark:bg-red-900/30',
            ],
        ];
    }

    private function getThemeTypes(): array
    {
        return [
            [
                'type' => 'gym',
                'label' => 'Gym (Musculation)',
                'icon' => 'heroicon-o-scale',
                'color' => 'primary',
                'description' => 'Weight training and resistance exercises performed in a gym environment. Includes machines, free weights, and cable exercises targeting specific muscle groups.',
                'examples' => 'Force maximale, Hypertrophie, Circuit Training',
            ],
            [
                'type' => 'cardio',
                'label' => 'Cardio',
                'icon' => 'heroicon-o-heart',
                'color' => 'danger',
                'description' => 'Cardiovascular conditioning exercises focused on heart rate elevation and endurance. Running, cycling, rowing, and interval-based training.',
                'examples' => 'HIIT, Endurance Run, Interval Sprints',
            ],
            [
                'type' => 'home',
                'label' => 'Home (Maison)',
                'icon' => 'heroicon-o-home',
                'color' => 'warning',
                'description' => 'Bodyweight exercises that can be performed at home with minimal or no equipment. Perfect for athletes without gym access.',
                'examples' => 'Bodyweight Circuit, Core Strength, Tabata',
            ],
            [
                'type' => 'mobility',
                'label' => 'Mobility (Mobilite)',
                'icon' => 'heroicon-o-arrow-path',
                'color' => 'success',
                'description' => 'Flexibility, joint mobility, and recovery-focused sessions. Injury prevention through stretching, foam rolling, and corrective exercises.',
                'examples' => 'Dynamic Stretching, Yoga Flow, Joint Mobility',
            ],
            [
                'type' => 'outdoor',
                'label' => 'Outdoor (Dehors)',
                'icon' => 'heroicon-o-sun',
                'color' => 'info',
                'description' => 'Training sessions designed for outdoor environments. Field work, park exercises, and sport-specific drills performed outside.',
                'examples' => 'Sprint Drills, Agility Work, Plyometrics',
            ],
        ];
    }

    private function getStats(): array
    {
        $themesByZone = WorkoutTheme::selectRaw('zone_color, count(*) as count')
            ->whereNotNull('zone_color')
            ->groupBy('zone_color')
            ->pluck('count', 'zone_color')
            ->toArray();

        $themesByType = WorkoutTheme::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        return [
            'total_themes' => WorkoutTheme::count(),
            'total_exercises' => Exercise::count(),
            'total_profiles' => PlayerProfile::count(),
            'total_zones' => IntensityZone::count(),
            'themes_by_zone' => $themesByZone,
            'themes_by_type' => $themesByType,
        ];
    }

    private function getAlgorithmSteps(): array
    {
        return [
            [
                'step' => 1,
                'title' => 'Profile Selection',
                'description' => 'The system identifies the user\'s player profile (e.g., GARDIEN, DEFENSEUR, FITNESS_FEMME). Each profile has a set of weighted theme assignments.',
            ],
            [
                'step' => 2,
                'title' => 'Theme Weighting',
                'description' => 'Each player profile is linked to multiple workout themes with percentage weights (e.g., Force maximale 30%, Hypertrophie 25%). These weights determine how often each theme appears in the programme.',
            ],
            [
                'step' => 3,
                'title' => 'Zone Assignment',
                'description' => 'Each theme belongs to an intensity zone (blue through red). The algorithm distributes sessions across zones to ensure periodised training with appropriate recovery.',
            ],
            [
                'step' => 4,
                'title' => 'Exercise Selection',
                'description' => 'Based on the selected theme, the system picks exercises matching the theme\'s type and the exercise count defined in the theme\'s rules. Exercises are randomised to provide variety.',
            ],
            [
                'step' => 5,
                'title' => 'Rule Application',
                'description' => 'The theme\'s training rules (sets, reps, recovery time, load type) are applied to each exercise in the session, creating a complete structured workout.',
            ],
        ];
    }
}
