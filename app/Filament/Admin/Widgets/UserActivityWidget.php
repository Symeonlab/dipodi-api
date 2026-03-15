<?php

namespace App\Filament\Admin\Widgets;

use App\Models\User;
use App\Models\UserProgress;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class UserActivityWidget extends BaseWidget
{
    protected static ?int $sort = 7;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $pollingInterval = '30s';

    protected function getTableHeading(): string
    {
        return __('filament.widgets.user_activity');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                UserProgress::query()
                    ->with('user')
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament.labels.name'))
                    ->searchable()
                    ->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament.labels.email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('workout_type')
                    ->label(__('filament.widgets.activity_type'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'kine' => 'success',
                        'maison' => 'warning',
                        'cardio' => 'purple',
                        'musculation' => 'info',
                        'bonus' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('duration_minutes')
                    ->label(__('filament.widgets.duration'))
                    ->suffix(' min')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('calories_burned')
                    ->label(__('filament.widgets.calories'))
                    ->suffix(' kcal')
                    ->numeric()
                    ->color('danger'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.widgets.logged'))
                    ->since()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated(false)
            ->emptyStateHeading(__('filament.widgets.no_activity'))
            ->emptyStateDescription(__('filament.widgets.no_activity_desc'))
            ->emptyStateIcon('heroicon-o-chart-bar');
    }
}
