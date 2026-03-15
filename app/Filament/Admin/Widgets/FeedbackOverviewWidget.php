<?php

namespace App\Filament\Admin\Widgets;

use App\Models\FeedbackSession;
use App\Models\FeedbackCategory;
use App\Models\FeedbackAnswer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class FeedbackOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 15;

    protected function getHeading(): ?string
    {
        return __('filament.widgets.feedback_overview');
    }

    protected function getStats(): array
    {
        $totalSessions = FeedbackSession::count();
        $completedSessions = FeedbackSession::where('status', 'completed')->count();
        $averageScore = FeedbackSession::where('status', 'completed')
            ->whereNotNull('average_score')
            ->avg('average_score');

        $completionRate = $totalSessions > 0
            ? round(($completedSessions / $totalSessions) * 100, 1)
            : 0;

        // Recent activity (last 7 days)
        $recentSessions = FeedbackSession::where('created_at', '>=', now()->subDays(7))->count();
        $previousWeekSessions = FeedbackSession::whereBetween('created_at', [now()->subDays(14), now()->subDays(7)])->count();

        $sessionsTrend = $previousWeekSessions > 0
            ? round((($recentSessions - $previousWeekSessions) / $previousWeekSessions) * 100, 1)
            : ($recentSessions > 0 ? 100 : 0);

        // Top category
        $topCategory = FeedbackSession::where('status', 'completed')
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->orderByDesc('count')
            ->with('category')
            ->first();

        return [
            Stat::make(__('filament.widgets.total_feedback_sessions'), $totalSessions)
                ->description("{$completedSessions} " . __('filament.widgets.completed_count', ['rate' => $completionRate]))
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, $recentSessions]),

            Stat::make(__('filament.widgets.average_score'), $averageScore ? number_format($averageScore, 1) . '/10' : __('filament.messages.na'))
                ->description(__('filament.widgets.across_completed_sessions'))
                ->descriptionIcon('heroicon-m-star')
                ->color($averageScore >= 7 ? 'success' : ($averageScore >= 5 ? 'warning' : 'info')),

            Stat::make(__('filament.widgets.this_week_label'), $recentSessions)
                ->description($sessionsTrend >= 0 ? "+{$sessionsTrend}% " . __('filament.widgets.vs_last_week') : "{$sessionsTrend}% " . __('filament.widgets.vs_last_week'))
                ->descriptionIcon($sessionsTrend >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($sessionsTrend >= 0 ? 'success' : 'danger'),

            Stat::make(__('filament.widgets.top_category'), $topCategory?->category?->name_en ?? __('filament.messages.na'))
                ->description($topCategory ? "{$topCategory->count} " . __('filament.widgets.sessions_count') : __('filament.widgets.no_data_yet'))
                ->descriptionIcon('heroicon-m-trophy')
                ->color('warning'),
        ];
    }
}
