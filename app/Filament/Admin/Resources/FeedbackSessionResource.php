<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FeedbackSessionResource\Pages;
use App\Models\FeedbackSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FeedbackSessionResource extends Resource
{
    protected static ?string $model = FeedbackSession::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 25;
    protected static ?string $recordTitleAttribute = 'session_uuid';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.feedback');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.feedback_sessions');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.feedback_session_singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.feedback_sessions');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'completed')->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament.labels.user'))
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament.labels.email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->copyable(),
                Tables\Columns\TextColumn::make('category.name_en')
                    ->label(__('filament.labels.category'))
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('answered_questions')
                    ->label(__('filament.labels.progress'))
                    ->formatStateUsing(fn ($record) => "{$record->answered_questions}/{$record->total_questions}")
                    ->badge()
                    ->color('purple'),
                Tables\Columns\TextColumn::make('average_score')
                    ->label(__('filament.labels.average_score'))
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) : '-')
                    ->badge()
                    ->color(fn ($state) => match (true) {
                        $state >= 7 => 'success',
                        $state >= 5 => 'warning',
                        $state !== null => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
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
                Tables\Filters\Filter::make('score_range')
                    ->form([
                        Forms\Components\TextInput::make('min_score')
                            ->label(__('filament.labels.min_score'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10),
                        Forms\Components\TextInput::make('max_score')
                            ->label(__('filament.labels.max_score'))
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(10),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['min_score'],
                                fn (Builder $query, $score): Builder => $query->where('average_score', '>=', $score),
                            )
                            ->when(
                                $data['max_score'],
                                fn (Builder $query, $score): Builder => $query->where('average_score', '<=', $score),
                            );
                    }),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label(__('filament.filters.joined_from')),
                        Forms\Components\DatePicker::make('until')
                            ->label(__('filament.filters.joined_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.feedback_sessions'))
            ->emptyStateDescription(__('filament.empty.feedback_sessions_desc'))
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.session_information'))
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label(__('filament.labels.user'))
                            ->icon('heroicon-o-user'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label(__('filament.labels.email'))
                            ->copyable()
                            ->icon('heroicon-o-envelope'),
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
                        Infolists\Components\TextEntry::make('session_uuid')
                            ->label(__('filament.labels.session_id'))
                            ->copyable()
                            ->fontFamily('mono'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('filament.sections.statistics'))
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Infolists\Components\TextEntry::make('total_questions')
                            ->label(__('filament.labels.total_questions')),
                        Infolists\Components\TextEntry::make('answered_questions')
                            ->label(__('filament.labels.answered')),
                        Infolists\Components\TextEntry::make('average_score')
                            ->label(__('filament.labels.average_score'))
                            ->formatStateUsing(fn ($state) => $state ? number_format($state, 2) . '/10' : __('filament.messages.na'))
                            ->badge()
                            ->color(fn ($state) => match (true) {
                                $state >= 7 => 'success',
                                $state >= 5 => 'warning',
                                $state !== null => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('filament.labels.created'))
                            ->dateTime('F j, Y g:i A'),
                        Infolists\Components\TextEntry::make('completed_at')
                            ->label(__('filament.labels.completed'))
                            ->dateTime('F j, Y g:i A')
                            ->placeholder(__('filament.labels.not_completed')),
                    ])
                    ->columns(5),

                Infolists\Components\Section::make(__('filament.sections.insights'))
                    ->icon('heroicon-o-light-bulb')
                    ->schema([
                        Infolists\Components\TextEntry::make('insights')
                            ->label('')
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->placeholder(__('filament.placeholders.no_insights')),
                    ])
                    ->collapsed()
                    ->visible(fn ($record) => !empty($record->insights)),

                Infolists\Components\Section::make(__('filament.sections.questions_answers'))
                    ->icon('heroicon-o-question-mark-circle')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('answers')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('question.question_en')
                                    ->label(__('filament.labels.question'))
                                    ->weight('bold')
                                    ->columnSpan(2),
                                Infolists\Components\TextEntry::make('question.question_fr')
                                    ->label(__('filament.labels.french'))
                                    ->color('gray')
                                    ->size('sm')
                                    ->columnSpan(2),
                                Infolists\Components\TextEntry::make('question.answer_type')
                                    ->label(__('filament.labels.type'))
                                    ->badge()
                                    ->color('purple'),
                                Infolists\Components\TextEntry::make('answer_value')
                                    ->label(__('filament.labels.answer'))
                                    ->badge()
                                    ->color('success')
                                    ->size('lg'),
                                Infolists\Components\TextEntry::make('created_at')
                                    ->label(__('filament.labels.answered_at'))
                                    ->dateTime('M d, Y H:i'),
                            ])
                            ->columns(4)
                            ->grid(1),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFeedbackSessions::route('/'),
            'view' => Pages\ViewFeedbackSession::route('/{record}'),
        ];
    }
}
