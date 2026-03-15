<?php

namespace App\Filament\Admin\Resources\FeedbackSessionResource\Widgets;

use App\Models\FeedbackSession;
use App\Models\FeedbackCategory;
use App\Models\FeedbackAnswer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FeedbackStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $totalSessions = FeedbackSession::count();
        $completedSessions = FeedbackSession::where('status', 'completed')->count();
        $inProgressSessions = FeedbackSession::where('status', 'in_progress')->count();
        $averageScore = FeedbackSession::where('status', 'completed')
            ->whereNotNull('average_score')
            ->avg('average_score');
        $totalAnswers = FeedbackAnswer::count();
        $activeCategories = FeedbackCategory::where('is_active', true)->count();

        // Get completion rate
        $completionRate = $totalSessions > 0
            ? round(($completedSessions / $totalSessions) * 100, 1)
            : 0;

        // Get sessions from last 7 days
        $recentSessions = FeedbackSession::where('created_at', '>=', now()->subDays(7))->count();

        return [
            Stat::make(__('filament.widgets.total_sessions'), $totalSessions)
                ->description(__('filament.widgets.all_feedback_sessions'))
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('primary'),

            Stat::make(__('filament.widgets.completed'), $completedSessions)
                ->description("{$completionRate}% " . __('filament.widgets.completion_rate'))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make(__('filament.widgets.in_progress'), $inProgressSessions)
                ->description(__('filament.widgets.pending_completion'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make(__('filament.widgets.average_score'), $averageScore ? number_format($averageScore, 1) . '/10' : 'N/A')
                ->description(__('filament.widgets.across_completed_sessions'))
                ->descriptionIcon('heroicon-m-star')
                ->color($averageScore >= 7 ? 'success' : ($averageScore >= 5 ? 'warning' : 'danger')),

            Stat::make(__('filament.widgets.total_answers'), number_format($totalAnswers))
                ->description(__('filament.widgets.individual_responses'))
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('info'),

            Stat::make(__('filament.widgets.recent_activity'), $recentSessions)
                ->description(__('filament.widgets.sessions_last_7_days'))
                ->descriptionIcon('heroicon-m-calendar')
                ->color('purple'),
        ];
    }
}
