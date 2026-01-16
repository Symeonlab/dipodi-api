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
    protected static ?string $navigationGroup = 'Workout Logic';
    protected static ?string $navigationLabel = 'Bonus Workout Rules';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'type';

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
                Forms\Components\Section::make('Rule Configuration')
                    ->description('Define the workout rule parameters')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Forms\Components\Select::make('level')
                            ->label('Difficulty Level')
                            ->options([
                                'DÉBUTANT' => 'Debutant (Beginner)',
                                'INTERMÉDIAIRE' => 'Intermediaire (Intermediate)',
                                'AVANCÉ' => 'Avance (Advanced)',
                                'ALL' => 'All Levels',
                            ])
                            ->required()
                            ->native(false),
                        Forms\Components\Select::make('type')
                            ->label('Workout Type')
                            ->options([
                                'ABDOS' => 'Abdos (Core)',
                                'POMPES' => 'Pompes (Push-ups)',
                                'GAINAGE' => 'Gainage (Planks)',
                                'GAINAGE + ABDOS' => 'Gainage + Abdos',
                                'GAINAGE + POMPES' => 'Gainage + Pompes',
                                'POMPES + ABDOS' => 'Pompes + Abdos',
                                'POMPES + GAINAGE' => 'Pompes + Gainage',
                                'ABDOS + GAINAGE' => 'Abdos + Gainage',
                                'ABDOS + POMPES' => 'Abdos + Pompes',
                            ])
                            ->required()
                            ->native(false)
                            ->searchable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Workout Parameters')
                    ->description('Set the workout intensity and rest periods')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\TextInput::make('sets')
                            ->label('Sets')
                            ->required()
                            ->placeholder('e.g., 3, 4, 5')
                            ->helperText('Number of sets to perform'),
                        Forms\Components\TextInput::make('reps')
                            ->label('Reps')
                            ->required()
                            ->placeholder('e.g., 10-20, 15, 20-30')
                            ->helperText('Repetitions per set (can be a range)'),
                        Forms\Components\TextInput::make('recovery')
                            ->label('Recovery Time')
                            ->required()
                            ->placeholder('e.g., 45 sec, 1 min')
                            ->helperText('Rest between sets'),
                        Forms\Components\TextInput::make('duration')
                            ->label('Total Duration')
                            ->placeholder('e.g., 12 MIN, 20 MIN')
                            ->helperText('Expected workout duration'),
                        Forms\Components\TextInput::make('exercise_count')
                            ->label('Exercise Count')
                            ->placeholder('e.g., 3-4, 4-5')
                            ->helperText('Number of exercises in the workout'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('level')
                    ->label('Level')
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
                    ->label('Type')
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
                    ->label('Sets')
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('reps')
                    ->label('Reps')
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('recovery')
                    ->label('Recovery')
                    ->alignCenter()
                    ->icon('heroicon-o-clock'),
                Tables\Columns\TextColumn::make('duration')
                    ->label('Duration')
                    ->alignCenter()
                    ->badge()
                    ->color('success')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('exercise_count')
                    ->label('Exercises')
                    ->alignCenter()
                    ->badge()
                    ->color('info')
                    ->placeholder('-'),
            ])
            ->defaultSort('level')
            ->groups([
                Tables\Grouping\Group::make('level')
                    ->label('Difficulty Level')
                    ->collapsible(),
                Tables\Grouping\Group::make('type')
                    ->label('Workout Type')
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label('Level')
                    ->options([
                        'DÉBUTANT' => 'Debutant',
                        'INTERMÉDIAIRE' => 'Intermediaire',
                        'AVANCÉ' => 'Avance',
                        'ALL' => 'All Levels',
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'ABDOS' => 'Abdos',
                        'POMPES' => 'Pompes',
                        'GAINAGE' => 'Gainage',
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
                    ->label('Combined Workouts')
                    ->query(fn (Builder $query): Builder => $query->where('type', 'LIKE', '%+%')),
            ])
            ->actions([
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
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
                        ->label('Update Level')
                        ->icon('heroicon-o-arrow-trending-up')
                        ->form([
                            Forms\Components\Select::make('level')
                                ->label('New Level')
                                ->options([
                                    'DÉBUTANT' => 'Debutant',
                                    'INTERMÉDIAIRE' => 'Intermediaire',
                                    'AVANCÉ' => 'Avance',
                                    'ALL' => 'All Levels',
                                ])
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            $records->each(fn ($record) => $record->update(['level' => $data['level']]));
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
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
            ->emptyStateHeading('No bonus workout rules')
            ->emptyStateDescription('Create rules to define bonus workout parameters for different levels.')
            ->emptyStateIcon('heroicon-o-fire');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Rule Configuration')
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

                Infolists\Components\Section::make('Workout Parameters')
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
                            ->placeholder('Not specified'),
                        Infolists\Components\TextEntry::make('exercise_count')
                            ->label('Exercises')
                            ->badge()
                            ->color('gray')
                            ->placeholder('Not specified'),
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
