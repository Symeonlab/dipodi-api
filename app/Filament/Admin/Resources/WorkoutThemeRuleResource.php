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
                Forms\Components\Section::make(__('filament.sections.theme_association'))
                    ->description(__('filament.sections.theme_association_desc'))
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\Select::make('workout_theme_id')
                            ->label(__('filament.labels.workout_theme'))
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
                                        'RENFORCEMENT' => __('filament.theme_types.renforcement'),
                                        'PUISSANCE' => __('filament.theme_types.puissance'),
                                        'ENDURANCE' => __('filament.theme_types.endurance'),
                                        'HYPERTROPHIE' => __('filament.theme_types.hypertrophie'),
                                        'EXPLOSIVITE' => __('filament.theme_types.explosivite'),
                                    ])
                                    ->required(),
                            ]),
                    ]),

                Forms\Components\Section::make(__('filament.sections.exercise_parameters'))
                    ->description(__('filament.sections.exercise_parameters_desc'))
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Forms\Components\TextInput::make('exercise_count')
                            ->label(__('filament.labels.exercise_count'))
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(20)
                            ->placeholder(__('filament.placeholders.exercise_count'))
                            ->helperText(__('filament.helper.exercise_count')),
                        Forms\Components\TextInput::make('sets')
                            ->label(__('filament.labels.sets'))
                            ->required()
                            ->placeholder(__('filament.placeholders.sets'))
                            ->helperText(__('filament.helper.sets')),
                        Forms\Components\TextInput::make('reps')
                            ->label(__('filament.labels.reps'))
                            ->required()
                            ->placeholder(__('filament.placeholders.reps'))
                            ->helperText(__('filament.helper.reps')),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('filament.sections.recovery_load'))
                    ->description(__('filament.sections.recovery_load_desc'))
                    ->icon('heroicon-o-clock')
                    ->schema([
                        Forms\Components\TextInput::make('recovery_time')
                            ->label(__('filament.labels.recovery_time'))
                            ->required()
                            ->placeholder(__('filament.placeholders.recovery'))
                            ->helperText(__('filament.helper.recovery')),
                        Forms\Components\Select::make('load_type')
                            ->label(__('filament.labels.load_type'))
                            ->options([
                                'LEGER' => __('filament.load_types.leger'),
                                'MODERE' => __('filament.load_types.modere'),
                                'LOURD' => __('filament.load_types.lourd'),
                                'MAX' => __('filament.load_types.max'),
                                'PROGRESSIF' => __('filament.load_types.progressif'),
                                'DEGRESSIF' => __('filament.load_types.degressif'),
                            ])
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.intensity_performance'))
                    ->description(__('filament.sections.intensity_performance_desc'))
                    ->icon('heroicon-o-signal')
                    ->schema([
                        Forms\Components\TextInput::make('mets')
                            ->label(__('filament.labels.mets'))
                            ->numeric()
                            ->step(0.1),
                        Forms\Components\TextInput::make('duration')
                            ->placeholder(__('filament.placeholders.duration')),
                        Forms\Components\TextInput::make('charges')
                            ->placeholder('e.g., 85-100%'),
                        Forms\Components\TextInput::make('speed_intensity')
                            ->label(__('filament.labels.speed_intensity'))
                            ->placeholder(__('filament.placeholders.speed')),
                        Forms\Components\TextInput::make('rpe')
                            ->label(__('filament.labels.rpe'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(10),
                        Forms\Components\TextInput::make('load_ua')
                            ->label(__('filament.labels.load_ua'))
                            ->numeric(),
                        Forms\Components\TextInput::make('impact')
                            ->label(__('filament.labels.impact'))
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(5),
                    ])
                    ->columns(4)
                    ->collapsible(),

                Forms\Components\Section::make(__('filament.sections.recovery_supercomp'))
                    ->description(__('filament.sections.recovery_supercomp_desc'))
                    ->icon('heroicon-o-arrow-trending-up')
                    ->schema([
                        Forms\Components\TextInput::make('sleep_requirement')
                            ->placeholder(__('filament.placeholders.sleep_duration')),
                        Forms\Components\TextInput::make('hydration')
                            ->placeholder('e.g., 1.00L'),
                        Forms\Components\TextInput::make('freshness_24h')
                            ->label(__('filament.labels.freshness_24h'))
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\TextInput::make('freshness_48h')
                            ->label(__('filament.labels.freshness_48h'))
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\TextInput::make('freshness_72h')
                            ->label(__('filament.labels.freshness_72h'))
                            ->numeric()
                            ->step(0.01),
                        Forms\Components\TextInput::make('supercomp_window')
                            ->label(__('filament.labels.supercomp_window'))
                            ->placeholder(__('filament.placeholders.supercomp')),
                        Forms\Components\TextInput::make('elastic_recoil')
                            ->placeholder('e.g., Diminue'),
                        Forms\Components\TextInput::make('cfa')
                            ->label('CFA')
                            ->placeholder('e.g., Moyen'),
                    ])
                    ->columns(4)
                    ->collapsible(),

                Forms\Components\Section::make(__('filament.sections.alerts_predictions'))
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([
                        Forms\Components\TextInput::make('daily_alert_threshold')
                            ->placeholder('e.g., 600 u.a.'),
                        Forms\Components\TextInput::make('weekly_alert_threshold')
                            ->placeholder('e.g., 1200 u.a.'),
                        Forms\Components\TextInput::make('gain_prediction')
                            ->placeholder('e.g., Explosivité & Réaction'),
                        Forms\Components\TextInput::make('injury_risk')
                            ->placeholder('e.g., Très Élevé'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('theme.name')
                    ->label(__('filament.sections.theme'))
                    ->badge()
                    ->color('info')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('theme.type')
                    ->label(__('filament.labels.type'))
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
                    ->label(__('filament.labels.exercises'))
                    ->alignCenter()
                    ->badge()
                    ->color('gray'),
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
                Tables\Columns\TextColumn::make('recovery_time')
                    ->label(__('filament.labels.recovery_time'))
                    ->icon('heroicon-o-clock'),
                Tables\Columns\TextColumn::make('load_type')
                    ->label(__('filament.labels.load_type'))
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
                        'LEGER' => __('filament.load_types.leger'),
                        'MODERE' => __('filament.load_types.modere'),
                        'LOURD' => __('filament.load_types.lourd'),
                        'MAX' => __('filament.load_types.max'),
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
            ->emptyStateHeading(__('filament.empty.theme_rules'))
            ->emptyStateDescription(__('filament.empty.theme_rules_desc'))
            ->emptyStateIcon('heroicon-o-cog-6-tooth');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.theme'))
                    ->schema([
                        Infolists\Components\TextEntry::make('theme.name')
                            ->label(__('filament.labels.theme_name'))
                            ->badge(),
                        Infolists\Components\TextEntry::make('theme.type')
                            ->label(__('filament.labels.theme_type'))
                            ->badge(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('filament.sections.parameters'))
                    ->schema([
                        Infolists\Components\TextEntry::make('exercise_count')
                            ->label(__('filament.labels.exercises')),
                        Infolists\Components\TextEntry::make('sets')
                            ->label(__('filament.labels.sets')),
                        Infolists\Components\TextEntry::make('reps')
                            ->label(__('filament.labels.reps')),
                        Infolists\Components\TextEntry::make('recovery_time')
                            ->label(__('filament.labels.recovery_time')),
                        Infolists\Components\TextEntry::make('load_type')
                            ->label(__('filament.labels.load_type'))
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
