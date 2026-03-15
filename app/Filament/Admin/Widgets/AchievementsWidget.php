<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Achievement;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class AchievementsWidget extends BaseWidget
{
    protected static ?int $sort = 8;
    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return __('filament.widgets.popular_achievements');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Achievement::query()
                    ->withCount('users')
                    ->orderByDesc('users_count')
            )
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->formatStateUsing(fn ($state) => $state ? "📱 {$state}" : '🏆'),

                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('filament.resources.achievements'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->label(__('filament.labels.category'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'workout' => 'primary',
                        'consistency' => 'success',
                        'milestone' => 'warning',
                        'nutrition' => 'info',
                        'special' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('points')
                    ->label(__('filament.widgets.points'))
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('users_count')
                    ->label(__('filament.widgets.earned_by'))
                    ->suffix(' ' . __('filament.resources.users'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('description_en')
                    ->label(__('filament.labels.description'))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('users_count', 'desc')
            ->paginated([5, 10, 25])
            ->defaultPaginationPageOption(5);
    }
}
