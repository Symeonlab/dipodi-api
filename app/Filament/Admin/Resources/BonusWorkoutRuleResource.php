<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BonusWorkoutRuleResource\Pages;
use App\Models\BonusWorkoutRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BonusWorkoutRuleResource extends Resource
{
    protected static ?string $model = BonusWorkoutRule::class;
    protected static ?string $navigationIcon = 'heroicon-o-fire';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'type';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.workout_logic');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.bonus_rules');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.bonus_rules');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['level', 'type'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Level' => $record->level,
            'Sets/Reps' => "{$record->sets} x {$record->reps}",
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.rule_config'))
                    ->description(__('filament.sections.rule_config_desc'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Forms\Components\Select::make('level')
                            ->label(__('filament.labels.difficulty_level'))
                            ->options([
                                'DÉBUTANT' => __('filament.difficulty_levels.debutant'),
                                'INTERMÉDIAIRE' => __('filament.difficulty_levels.intermediaire'),
                                'AVANCÉ' => __('filament.difficulty_levels.avance'),
                                'ALL' => __('filament.difficulty_levels.all'),
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('type')
                            ->label(__('filament.labels.workout_type'))
                            ->options([
                                'ABDOS' => __('filament.bonus_types.abdos'),
                                'POMPES' => __('filament.bonus_types.pompes'),
                                'GAINAGE' => __('filament.bonus_types.gainage'),
                                'GAINAGE + ABDOS' => __('filament.bonus_combo_types.gainage_abdos'),
                                'GAINAGE + POMPES' => __('filament.bonus_combo_types.gainage_pompes'),
                                'POMPES + ABDOS' => __('filament.bonus_combo_types.pompes_abdos'),
                                'POMPES + GAINAGE' => __('filament.bonus_combo_types.pompes_gainage'),
                                'ABDOS + GAINAGE' => __('filament.bonus_combo_types.abdos_gainage'),
                                'ABDOS + POMPES' => __('filament.bonus_combo_types.abdos_pompes'),
                            ])
                            ->required()
                            ->native(false)
                            ->searchable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.workout_parameters'))
                    ->description(__('filament.sections.workout_parameters_desc'))
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\TextInput::make('sets')
                            ->label(__('filament.labels.sets'))
                            ->required()
                            ->placeholder(__('filament.placeholders.sets_bonus'))
                            ->helperText(__('filament.helper.sets_bonus')),
                        Forms\Components\TextInput::make('reps')
                            ->label(__('filament.labels.reps'))
                            ->required()
                            ->placeholder(__('filament.placeholders.reps_bonus'))
                            ->helperText(__('filament.helper.reps_bonus')),
                        Forms\Components\TextInput::make('recovery')
                            ->label(__('filament.labels.recovery_time'))
                            ->required()
                            ->placeholder(__('filament.placeholders.recovery_bonus'))
                            ->helperText(__('filament.helper.recovery_bonus')),
                        Forms\Components\TextInput::make('duration')
                            ->label(__('filament.labels.total_duration'))
                            ->placeholder(__('filament.placeholders.duration_bonus'))
                            ->helperText(__('filament.helper.duration_bonus')),
                        Forms\Components\TextInput::make('exercise_count')
                            ->label(__('filament.labels.exercise_count'))
                            ->placeholder(__('filament.placeholders.exercise_count_bonus'))
                            ->helperText(__('filament.helper.exercise_count_bonus')),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('level')
                    ->label(__('filament.labels.level'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'DÉBUTANT' => 'success',
                        'INTERMÉDIAIRE' => 'warning',
                        'AVANCÉ' => 'danger',
                        'ALL' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'DÉBUTANT' => 'heroicon-o-academic-cap',
                        'INTERMÉDIAIRE' => 'heroicon-o-arrow-trending-up',
                        'AVANCÉ' => 'heroicon-o-fire',
                        'ALL' => 'heroicon-o-users',
                        default => 'heroicon-o-question-mark-circle',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.labels.type'))
                    ->badge()
                    ->color(fn (string $state): string => match (true) {
                        str_contains($state, '+') => 'purple',
                        $state === 'ABDOS' => 'info',
                        $state === 'POMPES' => 'danger',
                        $state === 'GAINAGE' => 'warning',
                        default => 'gray',
                    })
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sets')
                    ->label(__('filament.labels.sets'))
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('reps')
                    ->label(__('filament.labels.reps'))
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('recovery')
                    ->label(__('filament.labels.recovery'))
                    ->alignCenter()
                    ->icon('heroicon-o-clock'),
                Tables\Columns\TextColumn::make('duration')
                    ->label(__('filament.labels.duration'))
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('exercise_count')
                    ->label(__('filament.labels.exercises'))
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),
            ])
            ->defaultSort('level')
            ->groups([
                Tables\Grouping\Group::make('level')
                    ->label(__('filament.labels.difficulty_level'))
                    ->collapsible(),
                Tables\Grouping\Group::make('type')
                    ->label(__('filament.labels.workout_type'))
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label(__('filament.labels.level'))
                    ->options([
                        'DÉBUTANT' => __('filament.difficulty_levels.debutant'),
                        'INTERMÉDIAIRE' => __('filament.difficulty_levels.intermediaire'),
                        'AVANCÉ' => __('filament.difficulty_levels.avance'),
                        'ALL' => __('filament.difficulty_levels.all'),
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('filament.labels.type'))
                    ->options([
                        'ABDOS' => __('filament.bonus_types.abdos'),
                        'POMPES' => __('filament.bonus_types.pompes'),
                        'GAINAGE' => __('filament.bonus_types.gainage'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['values'])) {
                            return $query;
                        }
                        return $query->where(function ($q) use ($data) {
                            foreach ($data['values'] as $value) {
                                $q->orWhere('type', 'LIKE', "%{$value}%");
                            }
                        });
                    })
                    ->multiple(),
                Tables\Filters\Filter::make('combined_workouts')
                    ->label(__('filament.filters.combined_workouts'))
                    ->query(fn (Builder $query): Builder => $query->where('type', 'LIKE', '%+%')),
            ])
            ->actions([
                Tables\Actions\Action::make('duplicate')
                    ->label(__('filament.actions.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (BonusWorkoutRule $record) {
                        BonusWorkoutRule::create([
                            'level' => $record->level,
                            'type' => $record->type,
                            'sets' => $record->sets,
                            'reps' => $record->reps,
                            'recovery' => $record->recovery,
                            'duration' => $record->duration,
                            'exercise_count' => $record->exercise_count,
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateLevel')
                        ->label(__('filament.actions.update_level'))
                        ->icon('heroicon-o-arrow-trending-up')
                        ->form([
                            Forms\Components\Select::make('level')
                                ->label(__('filament.labels.new_level'))
                                ->options([
                                    'DÉBUTANT' => __('filament.difficulty_levels.debutant'),
                                    'INTERMÉDIAIRE' => __('filament.difficulty_levels.intermediaire'),
                                    'AVANCÉ' => __('filament.difficulty_levels.avance'),
                                    'ALL' => __('filament.difficulty_levels.all'),
                                ])
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            $records->each(fn ($record) => $record->update(['level' => $data['level']]));
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')
                        ->label(__('filament.actions.export_selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csv = "Level,Type,Sets,Reps,Recovery,Duration,Exercise Count\n";
                            foreach ($records as $record) {
                                $csv .= "\"{$record->level}\",\"{$record->type}\",\"{$record->sets}\",\"{$record->reps}\",\"{$record->recovery}\",\"{$record->duration}\",\"{$record->exercise_count}\"\n";
                            }
                            return response()->streamDownload(fn () => print($csv), 'bonus-workout-rules-export.csv');
                        }),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.bonus_rules'))
            ->emptyStateDescription(__('filament.empty.bonus_rules_desc'))
            ->emptyStateIcon('heroicon-o-fire');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.rule_config'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Infolists\Components\TextEntry::make('level')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'DÉBUTANT' => 'success',
                                'INTERMÉDIAIRE' => 'warning',
                                'AVANCÉ' => 'danger',
                                'ALL' => 'info',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('type')
                            ->badge()
                            ->color(fn (string $state): string => match (true) {
                                str_contains($state, '+') => 'purple',
                                $state === 'ABDOS' => 'info',
                                $state === 'POMPES' => 'danger',
                                $state === 'GAINAGE' => 'warning',
                                default => 'gray',
                            }),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make(__('filament.sections.workout_parameters'))
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Infolists\Components\TextEntry::make('sets')
                            ->badge()
                            ->color('success')
                            ->suffix(' sets'),
                        Infolists\Components\TextEntry::make('reps')
                            ->badge()
                            ->color('info')
                            ->suffix(' reps'),
                        Infolists\Components\TextEntry::make('recovery')
                            ->icon('heroicon-o-clock')
                            ->badge()
                            ->color('warning'),
                        Infolists\Components\TextEntry::make('duration')
                            ->badge()
                            ->color('purple')
                            ->placeholder(__('filament.placeholders.not_set')),
                        Infolists\Components\TextEntry::make('exercise_count')
                            ->label(__('filament.labels.exercises'))
                            ->badge()
                            ->color('gray')
                            ->placeholder(__('filament.placeholders.not_set')),
                    ])
                    ->columns(5),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBonusWorkoutRules::route('/'),
            'create' => Pages\CreateBonusWorkoutRule::route('/create'),
            'view' => Pages\ViewBonusWorkoutRule::route('/{record}'),
            'edit' => Pages\EditBonusWorkoutRule::route('/{record}/edit'),
        ];
    }
}
