<?php

namespace App\Filament\Admin\Widgets;

use App\Models\HealthAssessmentSession;
use App\Models\HealthAssessmentCategory;
use App\Models\HealthAssessmentQuestion;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HealthAssessmentOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 20;

    protected function getHeading(): ?string
    {
        return __('filament.widgets.health_assessment_overview');
    }

    protected function getStats(): array
    {
        $totalSessions = HealthAssessmentSession::count();
        $completedSessions = HealthAssessmentSession::where('status', 'completed')->count();
        $totalQuestions = HealthAssessmentQuestion::where('is_active', true)->count();
        $totalCategories = HealthAssessmentCategory::where('is_active', true)->count();

        $completionRate = $totalSessions > 0
            ? round(($completedSessions / $totalSessions) * 100, 1)
            : 0;

        // Recent activity (last 7 days)
        $recentSessions = HealthAssessmentSession::where('created_at', '>=', now()->subDays(7))->count();

        // Users with health concerns (positive answers to critical questions)
        $criticalConcernsCount = HealthAssessmentSession::where('status', 'completed')
            ->whereHas('answers', function ($q) {
                $q->whereIn('answer_value', ['oui', 'yes', '1', 'true'])
                  ->whereHas('question', fn ($q) => $q->where('is_critical', true));
            })
            ->count();

        return [
            Stat::make(__('filament.widgets.assessment_sessions'), $totalSessions)
                ->description("{$completedSessions} " . __('filament.widgets.completed_count', ['rate' => $completionRate]))
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, $recentSessions]),

            Stat::make(__('filament.widgets.questions_database'), $totalQuestions)
                ->description(__('filament.widgets.across_categories', ['count' => $totalCategories]))
                ->descriptionIcon('heroicon-m-question-mark-circle')
                ->color('info'),

            Stat::make(__('filament.widgets.this_week_label'), $recentSessions)
                ->description(__('filament.widgets.new_assessments_started'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color($recentSessions > 0 ? 'success' : 'gray'),

            Stat::make(__('filament.widgets.critical_concerns'), $criticalConcernsCount)
                ->description(__('filament.widgets.users_flagged_health'))
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($criticalConcernsCount > 0 ? 'danger' : 'success'),
        ];
    }
}
