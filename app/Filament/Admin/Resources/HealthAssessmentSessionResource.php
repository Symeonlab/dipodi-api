<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HealthAssessmentSessionResource\Pages;
use App\Models\HealthAssessmentSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HealthAssessmentSessionResource extends Resource
{
    protected static ?string $model = HealthAssessmentSession::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 31;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.health_assessment');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.health_sessions');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.health_sessions');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable(),
                Forms\Components\Select::make('status')
                    ->options([
                        'started' => __('filament.labels.started'),
                        'in_progress' => __('filament.labels.in_progress'),
                        'completed' => __('filament.labels.completed'),
                    ])
                    ->required(),
                Forms\Components\TextInput::make('total_questions')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('answered_questions')
                    ->numeric()
                    ->required(),
                Forms\Components\Textarea::make('insights')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('recommendations')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('filament.labels.id'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament.labels.user'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.email')
                    ->label(__('filament.labels.email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        return "{$record->answered_questions}/{$record->total_questions}";
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
                    ->sortable()
                    ->placeholder(__('filament.labels.not_completed')),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'started' => __('filament.labels.started'),
                        'in_progress' => __('filament.labels.in_progress'),
                        'completed' => __('filament.labels.completed'),
                    ]),
                Tables\Filters\Filter::make('has_concerns')
                    ->label(__('filament.labels.critical_concerns'))
                    ->query(function ($query) {
                        return $query->whereHas('answers', function ($q) {
                            $q->whereIn('answer_value', ['oui', 'yes', '1', 'true'])
                              ->whereHas('question', fn ($q) => $q->where('is_critical', true));
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.session_info'))
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label(__('filament.labels.user')),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label(__('filament.labels.email')),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'completed' => 'success',
                                'in_progress' => 'warning',
                                'started' => 'info',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('progress')
                            ->label(__('filament.labels.progress'))
                            ->state(function ($record): string {
                                $percentage = $record->total_questions > 0
                                    ? round(($record->answered_questions / $record->total_questions) * 100, 1)
                                    : 0;
                                return "{$record->answered_questions}/{$record->total_questions} ({$percentage}%)";
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('filament.labels.created_at'))
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('completed_at')
                            ->label(__('filament.labels.completed'))
                            ->dateTime()
                            ->placeholder(__('filament.labels.not_completed')),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('filament.sections.insights_recommendations'))
                    ->schema([
                        Infolists\Components\TextEntry::make('insights')
                            ->label(__('filament.labels.insights'))
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->columnSpanFull(),
                        Infolists\Components\TextEntry::make('recommendations')
                            ->label(__('filament.labels.recommendations'))
                            ->listWithLineBreaks()
                            ->bulleted()
                            ->columnSpanFull(),
                    ])
                    ->visible(fn ($record) => $record->status === 'completed'),

                Infolists\Components\Section::make(__('filament.sections.answers_by_category'))
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('answers')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('question.category.name_en')
                                    ->label(__('filament.labels.category'))
                                    ->badge()
                                    ->color('info'),
                                Infolists\Components\TextEntry::make('question.question_en')
                                    ->label(__('filament.labels.question')),
                                Infolists\Components\TextEntry::make('answer_value')
                                    ->label(__('filament.labels.answer'))
                                    ->badge()
                                    ->color(fn ($state): string =>
                                        in_array(strtolower($state), ['oui', 'yes', '1', 'true']) ? 'danger' : 'success'
                                    ),
                                Infolists\Components\IconEntry::make('question.is_critical')
                                    ->label(__('filament.labels.critical'))
                                    ->boolean()
                                    ->trueIcon('heroicon-o-exclamation-triangle')
                                    ->falseIcon('heroicon-o-minus'),
                            ])
                            ->columns(4)
                            ->columnSpanFull(),
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
            'index' => Pages\ListHealthAssessmentSessions::route('/'),
            'view' => Pages\ViewHealthAssessmentSession::route('/{record}'),
        ];
    }
}
