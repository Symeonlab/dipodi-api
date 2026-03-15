<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FeedbackSessionsRelationManager extends RelationManager
{
    protected static string $relationship = 'feedbackSessions';
    protected static ?string $title = null;
    protected static ?string $icon = 'heroicon-o-clipboard-document-list';

    public static function getTitle($ownerRecord, string $pageClass): string
    {
        return __('filament.resources.feedback_sessions');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('session_uuid')
            ->columns([
                Tables\Columns\TextColumn::make('category.name_en')
                    ->label(__('filament.labels.category'))
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('answered_questions')
                    ->label(__('filament.labels.answered'))
                    ->formatStateUsing(fn ($record) => "{$record->answered_questions}/{$record->total_questions}")
                    ->badge()
                    ->color('purple'),
                Tables\Columns\TextColumn::make('average_score')
                    ->label(__('filament.labels.average_score'))
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) . '/10' : '-')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 7 => 'success',
                        $state >= 5 => 'warning',
                        $state !== null => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label(__('filament.labels.completed'))
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created'))
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => __('filament.labels.completed'),
                        'in_progress' => __('filament.labels.in_progress'),
                    ]),
                Tables\Filters\SelectFilter::make('category_id')
                    ->label(__('filament.labels.category'))
                    ->relationship('category', 'name_en')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(__('filament.sections.feedback_session_details'))
                    ->infolist([
                        Infolists\Components\Section::make(__('filament.sections.session_overview'))
                            ->schema([
                                Infolists\Components\TextEntry::make('category.name_en')
                                    ->label(__('filament.labels.category'))
                                    ->badge()
                                    ->color('info'),
                                Infolists\Components\TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'completed' => 'success',
                                        'in_progress' => 'warning',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('answered_questions')
                                    ->label(__('filament.labels.questions_answered'))
                                    ->formatStateUsing(fn ($record) => "{$record->answered_questions} of {$record->total_questions}"),
                                Infolists\Components\TextEntry::make('average_score')
                                    ->label(__('filament.labels.average_score'))
                                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) . '/10' : __('filament.messages.na')),
                                Infolists\Components\TextEntry::make('completed_at')
                                    ->label(__('filament.labels.completed'))
                                    ->dateTime('F j, Y g:i A'),
                                Infolists\Components\TextEntry::make('session_uuid')
                                    ->label(__('filament.labels.session_id'))
                                    ->copyable()
                                    ->fontFamily('mono'),
                            ])
                            ->columns(3),

                        Infolists\Components\Section::make(__('filament.sections.insights'))
                            ->schema([
                                Infolists\Components\TextEntry::make('insights')
                                    ->label('')
                                    ->listWithLineBreaks()
                                    ->bulleted()
                                    ->placeholder(__('filament.placeholders.no_insights')),
                            ])
                            ->collapsed()
                            ->visible(fn ($record) => !empty($record->insights)),

                        Infolists\Components\Section::make(__('filament.sections.answers'))
                            ->schema([
                                Infolists\Components\RepeatableEntry::make('answers')
                                    ->schema([
                                        Infolists\Components\TextEntry::make('question.question_en')
                                            ->label(__('filament.labels.question'))
                                            ->weight('bold')
                                            ->columnSpan(2),
                                        Infolists\Components\TextEntry::make('answer_value')
                                            ->label(__('filament.labels.answer'))
                                            ->badge()
                                            ->color('success'),
                                    ])
                                    ->columns(3),
                            ]),
                    ]),
            ])
            ->emptyStateHeading(__('filament.empty.feedback_sessions'))
            ->emptyStateDescription(__('filament.empty.feedback_sessions_user_desc'))
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}
