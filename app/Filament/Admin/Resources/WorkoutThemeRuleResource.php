<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WorkoutThemeRuleResource\Pages;
use App\Models\WorkoutThemeRule;
use App\Models\WorkoutTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WorkoutThemeRuleResource extends Resource
{
    protected static ?string $model = WorkoutThemeRule::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.workout_logic');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.theme_rules');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.theme_rules');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'gray';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Theme Association')
                    ->description('Link this rule to a workout theme')
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\Select::make('workout_theme_id')
                            ->label('Workout Theme')
                            ->relationship('theme', 'name')
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->required(),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'RENFORCEMENT' => 'Renforcement',
                                        'PUISSANCE' => 'Puissance',
                                        'ENDURANCE' => 'Endurance',
                                        'HYPERTROPHIE' => 'Hypertrophie',
                                        'EXPLOSIVITE' => 'Explosivite',
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make('Exercise Parameters')
                    ->description('Define the workout structure')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\TextInput::make('exercise_count')
                            ->label('Exercise Count')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(20)
                            ->placeholder('e.g., 4, 5, 6')
                            ->helperText('Number of exercises per session'),
                        Forms\Components\TextInput::make('sets')
                            ->label('Sets')
                            ->required()
                            ->placeholder('e.g., 3, 4, 5')
                            ->helperText('Number of sets per exercise'),
                        Forms\Components\TextInput::make('reps')
                            ->label('Reps')
                            ->required()
                            ->placeholder('e.g., 8-12, 15, 20')
                            ->helperText('Repetitions per set'),
                    ])
                    ->columns(3),

                Forms\Components\Section::make('Recovery & Load')
                    ->description('Rest periods and intensity settings')
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\TextInput::make('recovery_time')
                            ->label('Recovery Time')
                            ->required()
                            ->placeholder('e.g., 60s, 90s, 2min')
                            ->helperText('Rest between sets'),
                        Forms\Components\Select::make('load_type')
                            ->label('Load Type')
                            ->options([
                                'LEGER' => 'Leger (Light)',
                                'MODERE' => 'Modere (Moderate)',
                                'LOURD' => 'Lourd (Heavy)',
                                'MAX' => 'Maximum',
                                'PROGRESSIF' => 'Progressif',
                                'DEGRESSIF' => 'Degressif',
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('theme.name')
                    ->label('Theme')
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('theme.type')
                    ->label('Type')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'RENFORCEMENT' => 'success',
                        'PUISSANCE' => 'danger',
                        'ENDURANCE' => 'info',
                        'HYPERTROPHIE' => 'warning',
                        'EXPLOSIVITE' => 'purple',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('exercise_count')
                    ->label('Exercises')
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
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
                Tables\Columns\TextColumn::make('recovery_time')
                    ->label('Recovery')
                    ->icon('heroicon-o-clock'),
                Tables\Columns\TextColumn::make('load_type')
                    ->label('Load')
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'LEGER' => 'success',
                        'MODERE' => 'warning',
                        'LOURD' => 'danger',
                        'MAX' => 'purple',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('theme')
                    ->relationship('theme', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('load_type')
                    ->options([
                        'LEGER' => 'Leger',
                        'MODERE' => 'Modere',
                        'LOURD' => 'Lourd',
                        'MAX' => 'Maximum',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('No workout theme rules')
            ->emptyStateDescription('Create rules to define workout parameters for each theme.')
            ->emptyStateIcon('heroicon-o-cog-6-tooth');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Theme')
                    ->schema([
                        Infolists\Components\TextEntry::make('theme.name')
                            ->label('Theme Name')
                            ->badge(),
                        Infolists\Components\TextEntry::make('theme.type')
                            ->label('Theme Type')
                            ->badge(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Parameters')
                    ->schema([
                        Infolists\Components\TextEntry::make('exercise_count')
                            ->label('Exercises'),
                        Infolists\Components\TextEntry::make('sets')
                            ->label('Sets'),
                        Infolists\Components\TextEntry::make('reps')
                            ->label('Reps'),
                        Infolists\Components\TextEntry::make('recovery_time')
                            ->label('Recovery'),
                        Infolists\Components\TextEntry::make('load_type')
                            ->label('Load Type')
                            ->badge(),
                    ])
                    ->columns(5),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkoutThemeRules::route('/'),
            'create' => Pages\CreateWorkoutThemeRule::route('/create'),
            'edit' => Pages\EditWorkoutThemeRule::route('/{record}/edit'),
        ];
    }
}
