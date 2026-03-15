<?php

namespace App\Filament\Admin\Widgets;

use App\Models\OnboardingOption;
use App\Models\UserProfile;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OnboardingStatsWidget extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';
    protected static ?int $sort = 4;

    protected function getHeading(): ?string
    {
        return __('filament.widgets.onboarding_stats');
    }

    protected function getStats(): array
    {
        $totalOptions = OnboardingOption::count();
        $totalProfiles = UserProfile::count();
        $onboardedUsers = UserProfile::where('is_onboarding_complete', true)->count();
        $pendingUsers = UserProfile::where('is_onboarding_complete', false)->count();

        $completionRate = $totalProfiles > 0
            ? round(($onboardedUsers / $totalProfiles) * 100)
            : 0;

        // Get discipline distribution
        $footballUsers = UserProfile::where('discipline', 'FOOTBALL')->count();
        $fitnessUsers = UserProfile::where('discipline', 'FITNESS')->count();
        $padelUsers = UserProfile::where('discipline', 'PADEL')->count();

        return [
            Stat::make(__('filament.resources.onboarding_options'), $totalOptions)
                ->description(__('filament.widgets.total_config_options'))
                ->descriptionIcon('heroicon-m-cog-6-tooth')
                ->color('info'),

            Stat::make(__('filament.widgets.onboarding_completion'), "{$completionRate}%")
                ->description("{$onboardedUsers} / {$totalProfiles} " . __('filament.resources.users'))
                ->descriptionIcon($completionRate >= 70 ? 'heroicon-m-check-circle' : 'heroicon-m-exclamation-circle')
                ->color($completionRate >= 70 ? 'success' : 'warning')
                ->chart($this->getCompletionChart()),

            Stat::make(__('filament.widgets.pending_onboarding'), $pendingUsers)
                ->description(__('filament.widgets.users_not_onboarded'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingUsers > 10 ? 'danger' : 'gray'),

            Stat::make(__('filament.widgets.top_discipline'), $this->getTopDiscipline($footballUsers, $fitnessUsers, $padelUsers))
                ->description("Football: {$footballUsers} | Fitness: {$fitnessUsers} | Padel: {$padelUsers}")
                ->descriptionIcon('heroicon-m-trophy')
                ->color('purple'),
        ];
    }

    private function getCompletionChart(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = UserProfile::whereDate('updated_at', $date)
                ->where('is_onboarding_complete', true)
                ->count();
        }
        return $data;
    }

    private function getTopDiscipline(int $football, int $fitness, int $padel): string
    {
        $max = max($football, $fitness, $padel);
        if ($max === 0) return __('filament.widgets.none');
        if ($max === $football) return __('filament.disciplines.football');
        if ($max === $fitness) return __('filament.disciplines.fitness');
        return 'Padel';
    }
}
