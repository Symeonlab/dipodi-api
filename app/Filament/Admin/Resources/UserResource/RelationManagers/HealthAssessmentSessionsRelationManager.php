<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HealthAssessmentSessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'healthAssessmentSessions';
    protected static ?string $title = null;
    protected static ?string $icon = 'heroicon-o-heart';

    public static function getTitle($ownerRecord, string $pageClass): string
    {
        return __('filament.resources.health_sessions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.labels.id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        'started' => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('progress')
                    ->label(__('filament.labels.progress'))
                    ->state(function ($record): string {
                        $percentage = $record->total_questions > 0
                            ? round(($record->answered_questions / $record->total_questions) * 100, 1)
                            : 0;
                        return "{$percentage}%";
                    })
                    ->badge()
                    ->color(fn ($record): string =>
                        $record->answered_questions >= $record->total_questions ? 'success' : 'warning'
                    ),
                Tables\Columns\TextColumn::make('concerns_count')
                    ->label(__('filament.labels.total_concerns'))
                    ->state(function ($record) {
                        return $record->answers()
                            ->whereIn('answer_value', ['oui', 'yes', '1', 'true'])
                            ->count();
                    })
                    ->badge()
                    ->color(fn ($state): string => $state > 10 ? 'danger' : ($state > 5 ? 'warning' : 'success')),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created'))
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('filament.labels.completed'))
                    ->dateTime()
                    ->placeholder('-'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'started' => __('filament.labels.started'),
                        'in_progress' => __('filament.labels.in_progress'),
                        'completed' => __('filament.labels.completed'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(__('filament.sections.assessment_details'))
                    ->infolist([
                        Infolists\Components\Section::make(__('filament.sections.session_summary'))
                            ->schema([
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'completed' => 'success',
                                        'in_progress' => 'warning',
                                        'started' => 'info',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('progress')
                                    ->state(function ($record): string {
                                        return "{$record->answered_questions}/{$record->total_questions}";
                                    }),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('filament.labels.created'))
                                    ->dateTime(),
                                Infolists\Components\TextEntry::make('completed_at')
                                    ->label(__('filament.labels.completed'))
                                    ->dateTime()
                                    ->placeholder(__('filament.labels.not_completed')),
                            ])
                            ->columns(4),

                        Infolists\Components\Section::make(__('filament.sections.insights'))
                            ->schema([
                                Infolists\Components\TextEntry::make('insights')
                                    ->listWithLineBreaks()
                                    ->bulleted(),
                                Infolists\Components\TextEntry::make('recommendations')
                                    ->listWithLineBreaks()
                                    ->bulleted(),
                            ])
                            ->columns(2)
                            ->visible(fn ($record) => $record->status === 'completed'),

                        Infolists\Components\Section::make(__('filament.sections.positive_responses'))
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('answers')
                                    ->label('')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('question.category.name_en')
                                            ->label(__('filament.labels.category'))
                                            ->badge()
                                            ->color('info'),
                                        Infolists\Components\TextEntry::make('question.question_en')
                                            ->label(__('filament.labels.question'))
                                            ->columnSpan(2),
                                        Infolists\Components\IconEntry::make('question.is_critical')
                                            ->label(__('filament.labels.critical'))
                                            ->boolean()
                                            ->trueIcon('heroicon-o-exclamation-triangle')
                                            ->falseIcon('heroicon-o-minus'),
                                    ])
                                    ->columns(4)
                                    ->columnSpanFull()
                                    ->getStateUsing(function ($record) {
                                        return $record->answers()
                                            ->whereIn('answer_value', ['oui', 'yes', '1', 'true'])
                                            ->with('question.category')
                                            ->get();
                                    }),
                            ]),
                    ]),
            ])
            ->emptyStateHeading(__('filament.empty.health_assessments'))
            ->emptyStateDescription(__('filament.empty.health_assessments_user_desc'))
            ->emptyStateIcon('heroicon-o-heart');
    }
}
