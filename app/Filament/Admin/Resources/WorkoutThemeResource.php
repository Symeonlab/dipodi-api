<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WorkoutThemeResource\Pages;
use App\Models\WorkoutTheme;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkoutThemeResource extends Resource
{
    protected static ?string $model = WorkoutTheme::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.workout_logic');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.workout_themes');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.workout_theme');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.workout_themes');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.theme_info'))
                    ->description(__('filament.sections.theme_info_desc'))
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.labels.theme_name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.theme_name'))
                            ->columnSpan(2),
                        Forms\Components\Select::make('type')
                            ->label(__('filament.labels.theme_type'))
                            ->options([
                                'gym' => __('filament.theme_types.gym'),
                                'cardio' => __('filament.theme_types.cardio'),
                                'home' => __('filament.theme_types.home'),
                                'mobility' => __('filament.theme_types.mobility'),
                                'outdoor' => __('filament.theme_types.outdoor'),
                            ])
                            ->required()
                            ->native(false)
                            ->helperText('Gym = weight/resistance training | Cardio = heart rate & endurance | Home = bodyweight, no equipment | Mobility = flexibility & recovery | Outdoor = field drills & plyometrics')
                            ->columnSpan(1),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('filament.sections.programme_details'))
                    ->description(__('filament.sections.programme_details_desc'))
                    ->icon('heroicon-o-signal')
                    ->schema([
                        Forms\Components\Select::make('discipline')
                            ->options([
                                'football' => __('filament.disciplines.football'),
                                'padel' => __('filament.disciplines.padel'),
                                'fitness_women' => __('filament.disciplines.fitness_women'),
                                'fitness_men' => __('filament.disciplines.fitness_men'),
                            ])
                            ->nullable()
                            ->native(false),
                        Forms\Components\Select::make('zone_color')
                            ->label(__('filament.labels.intensity_zone'))
                            ->options([
                                'blue' => __('filament.zone_colors.blue'),
                                'green' => __('filament.zone_colors.green'),
                                'yellow' => __('filament.zone_colors.yellow'),
                                'orange' => __('filament.zone_colors.orange'),
                                'red' => __('filament.zone_colors.red'),
                            ])
                            ->nullable()
                            ->native(false)
                            ->helperText('Blue (Zone 1) = Recovery 50-60% RPE 1-2 | Green (Zone 2) = Endurance 60-70% RPE 3-4 | Yellow (Zone 3) = Match Rhythm 70-80% RPE 5-6 | Orange (Zone 4) = High Intensity 80-90% RPE 7-8 | Red (Zone 5) = Maximum 90-100% RPE 9-10'),
                        Forms\Components\TextInput::make('quality_method')
                            ->label(__('filament.labels.quality_method'))
                            ->placeholder(__('filament.placeholders.quality_method')),
                        Forms\Components\TextInput::make('display_name')
                            ->label(__('filament.labels.display_name'))
                            ->placeholder(__('filament.placeholders.display_name')),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make(__('filament.sections.training_rules'))
                    ->description(__('filament.sections.training_rules_desc'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->relationship('rules')
                    ->schema([
                        // --- Existing Training Parameters ---
                        Forms\Components\Fieldset::make(__('filament.sections.training_parameters'))
                            ->schema([
                                Forms\Components\TextInput::make('exercise_count')
                                    ->label(__('filament.labels.exercise_count'))
                                    ->numeric()
                                    ->required()
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
                                Forms\Components\TextInput::make('recovery_time')
                                    ->label(__('filament.labels.recovery_time'))
                                    ->required()
                                    ->placeholder(__('filament.placeholders.recovery'))
                                    ->helperText(__('filament.helper.recovery')),
                                Forms\Components\TextInput::make('load_type')
                                    ->label(__('filament.labels.load_type'))
                                    ->required()
                                    ->placeholder(__('filament.placeholders.load_type'))
                                    ->helperText(__('filament.helper.load_type')),
                                Forms\Components\TextInput::make('charges')
                                    ->label(__('filament.labels.charges'))
                                    ->placeholder(__('filament.placeholders.charges'))
                                    ->helperText(__('filament.helper.charges')),
                                Forms\Components\TextInput::make('speed_intensity')
                                    ->label(__('filament.labels.speed_intensity'))
                                    ->placeholder(__('filament.placeholders.speed'))
                                    ->helperText(__('filament.helper.speed_intensity')),
                            ])
                            ->columns(3),

                        // --- Metabolic Parameters ---
                        Forms\Components\Fieldset::make(__('filament.sections.metabolic_parameters'))
                            ->schema([
                                Forms\Components\TextInput::make('mets')
                                    ->label(__('filament.labels.mets'))
                                    ->numeric()
                                    ->step(0.1)
                                    ->placeholder(__('filament.placeholders.mets'))
                                    ->helperText(__('filament.helper.mets')),
                                Forms\Components\TextInput::make('duration')
                                    ->label(__('filament.labels.duration'))
                                    ->placeholder(__('filament.placeholders.duration'))
                                    ->helperText(__('filament.helper.duration')),
                                Forms\Components\TextInput::make('rpe')
                                    ->label(__('filament.labels.rpe'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(10)
                                    ->placeholder(__('filament.placeholders.rpe'))
                                    ->helperText(__('filament.helper.rpe')),
                                Forms\Components\TextInput::make('load_ua')
                                    ->label(__('filament.labels.load_ua'))
                                    ->numeric()
                                    ->placeholder(__('filament.placeholders.load_ua'))
                                    ->helperText(__('filament.helper.load_ua')),
                                Forms\Components\TextInput::make('impact')
                                    ->label(__('filament.labels.impact'))
                                    ->numeric()
                                    ->minValue(1)
                                    ->maxValue(5)
                                    ->placeholder(__('filament.placeholders.impact'))
                                    ->helperText(__('filament.helper.impact')),
                            ])
                            ->columns(3),

                        // --- Recovery & Freshness ---
                        Forms\Components\Fieldset::make(__('filament.sections.recovery_freshness'))
                            ->schema([
                                Forms\Components\TextInput::make('sleep_requirement')
                                    ->label(__('filament.labels.sleep_requirement'))
                                    ->placeholder(__('filament.placeholders.sleep_duration'))
                                    ->helperText(__('filament.helper.sleep_requirement')),
                                Forms\Components\TextInput::make('hydration')
                                    ->label(__('filament.labels.hydration'))
                                    ->placeholder(__('filament.placeholders.hydration'))
                                    ->helperText(__('filament.helper.hydration')),
                                Forms\Components\TextInput::make('freshness_24h')
                                    ->label(__('filament.labels.freshness_24h'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder(__('filament.placeholders.freshness_24h'))
                                    ->helperText(__('filament.helper.freshness_24h')),
                                Forms\Components\TextInput::make('freshness_48h')
                                    ->label(__('filament.labels.freshness_48h'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder(__('filament.placeholders.freshness_48h'))
                                    ->helperText(__('filament.helper.freshness_48h')),
                                Forms\Components\TextInput::make('freshness_72h')
                                    ->label(__('filament.labels.freshness_72h'))
                                    ->numeric()
                                    ->step(0.01)
                                    ->placeholder(__('filament.placeholders.freshness_72h'))
                                    ->helperText(__('filament.helper.freshness_72h')),
                            ])
                            ->columns(3),

                        // --- Performance Science ---
                        Forms\Components\Fieldset::make(__('filament.sections.performance_science'))
                            ->schema([
                                Forms\Components\TextInput::make('supercomp_window')
                                    ->label(__('filament.labels.supercomp_window'))
                                    ->placeholder(__('filament.placeholders.supercomp'))
                                    ->helperText(__('filament.helper.supercomp_window')),
                                Forms\Components\TextInput::make('gain_prediction')
                                    ->label(__('filament.labels.gain_prediction'))
                                    ->placeholder(__('filament.placeholders.gain_prediction'))
                                    ->helperText(__('filament.helper.gain_prediction')),
                                Forms\Components\Select::make('injury_risk')
                                    ->label(__('filament.labels.injury_risk'))
                                    ->options([
                                        'Nul' => __('filament.injury_risks.nul'),
                                        'Très Faible' => __('filament.injury_risks.tres_faible'),
                                        'Tres Faible' => __('filament.injury_risks.tres_faible_alt'),
                                        'Faible' => __('filament.injury_risks.faible'),
                                        'Moyen' => __('filament.injury_risks.moyen'),
                                        'Élevé' => __('filament.injury_risks.eleve'),
                                        'Eleve' => __('filament.injury_risks.eleve_alt'),
                                        'Très Élevé' => __('filament.injury_risks.tres_eleve'),
                                        'Tres Eleve' => __('filament.injury_risks.tres_eleve_alt'),
                                        'Critique' => __('filament.injury_risks.critique'),
                                    ])
                                    ->native(false)
                                    ->searchable()
                                    ->helperText(__('filament.helper.injury_risk')),
                            ])
                            ->columns(3),

                        // --- Load Thresholds ---
                        Forms\Components\Fieldset::make(__('filament.sections.load_thresholds'))
                            ->schema([
                                Forms\Components\TextInput::make('daily_alert_threshold')
                                    ->label(__('filament.labels.daily_alert_threshold'))
                                    ->placeholder(__('filament.placeholders.daily_alert_threshold'))
                                    ->helperText(__('filament.helper.daily_alert_threshold')),
                                Forms\Components\TextInput::make('weekly_alert_threshold')
                                    ->label(__('filament.labels.weekly_alert_threshold'))
                                    ->placeholder(__('filament.placeholders.weekly_alert_threshold'))
                                    ->helperText(__('filament.helper.weekly_alert_threshold')),
                                Forms\Components\TextInput::make('elastic_recoil')
                                    ->label(__('filament.labels.elastic_recoil'))
                                    ->placeholder(__('filament.placeholders.elastic_recoil'))
                                    ->helperText(__('filament.helper.elastic_recoil')),
                                Forms\Components\TextInput::make('cfa')
                                    ->label(__('filament.labels.cfa'))
                                    ->placeholder(__('filament.placeholders.cfa'))
                                    ->helperText(__('filament.helper.cfa')),
                            ])
                            ->columns(2),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.labels.theme_name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('zone_color')
                    ->label(__('filament.labels.intensity_zone'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'red' => 'danger',
                        'orange' => 'warning',
                        'yellow' => 'warning',
                        'green' => 'success',
                        'blue' => 'info',
                        default => 'gray',
                    })
                    ->toggleable(),
                Tables\Columns\TextColumn::make('type')
                    ->label(__('filament.labels.type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'gym' => 'primary',
                        'cardio' => 'danger',
                        'home' => 'warning',
                        'mobility' => 'success',
                        'outdoor' => 'info',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'gym' => 'heroicon-o-scale',
                        'cardio' => 'heroicon-o-heart',
                        'home' => 'heroicon-o-home',
                        'mobility' => 'heroicon-o-arrow-path',
                        'outdoor' => 'heroicon-o-sun',
                        default => 'heroicon-o-sparkles',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('rules.exercise_count')
                    ->label(__('filament.labels.exercises'))
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('rules.sets')
                    ->label(__('filament.labels.sets')),
                Tables\Columns\TextColumn::make('rules.reps')
                    ->label(__('filament.labels.reps')),
                Tables\Columns\TextColumn::make('rules.recovery_time')
                    ->label(__('filament.labels.recovery_time'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.load_type')
                    ->label(__('filament.labels.load_type'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.charges')
                    ->label(__('filament.labels.charges'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.speed_intensity')
                    ->label(__('filament.labels.speed_intensity'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.mets')
                    ->label(__('filament.labels.mets'))
                    ->numeric(1)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.duration')
                    ->label(__('filament.labels.duration'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.rpe')
                    ->label(__('filament.labels.rpe'))
                    ->badge()
                    ->color(fn (?int $state): string => match (true) {
                        $state === null => 'gray',
                        $state <= 3 => 'success',
                        $state <= 6 => 'warning',
                        $state <= 8 => 'warning',
                        default => 'danger',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.load_ua')
                    ->label(__('filament.labels.load_ua'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.impact')
                    ->label(__('filament.labels.impact'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.injury_risk')
                    ->label(__('filament.labels.injury_risk'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'Nul' => 'gray',
                        'Très Faible', 'Tres Faible' => 'success',
                        'Faible' => 'success',
                        'Moyen' => 'warning',
                        'Élevé', 'Eleve' => 'danger',
                        'Très Élevé', 'Tres Eleve' => 'danger',
                        'Critique' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.supercomp_window')
                    ->label(__('filament.labels.supercomp_window'))
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.freshness_24h')
                    ->label(__('filament.labels.freshness_24h'))
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.freshness_48h')
                    ->label(__('filament.labels.freshness_48h'))
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('rules.freshness_72h')
                    ->label(__('filament.labels.freshness_72h'))
                    ->numeric(2)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('playerProfiles_count')
                    ->label(__('filament.labels.profile'))
                    ->counts('playerProfiles')
                    ->badge()
                    ->color('purple'),
            ])
            ->defaultSort('type')
            ->defaultGroup('type')
            ->groups([
                Tables\Grouping\Group::make('type')
                    ->label(__('filament.labels.type'))
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'gym' => __('filament.theme_types.gym'),
                        'cardio' => __('filament.theme_types.cardio'),
                        'home' => __('filament.theme_types.home'),
                        'mobility' => __('filament.theme_types.mobility'),
                        'outdoor' => __('filament.theme_types.outdoor'),
                    ])
                    ->multiple(),
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
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.theme'))
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('filament.labels.name'))
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('type')
                            ->label(__('filament.labels.type'))
                            ->badge(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('filament.sections.training_parameters'))
                    ->icon('heroicon-o-cog-6-tooth')
                    ->schema([
                        Infolists\Components\TextEntry::make('rules.exercise_count')
                            ->label(__('filament.labels.exercise_count'))
                            ->badge(),
                        Infolists\Components\TextEntry::make('rules.sets')
                            ->label(__('filament.labels.sets')),
                        Infolists\Components\TextEntry::make('rules.reps')
                            ->label(__('filament.labels.reps')),
                        Infolists\Components\TextEntry::make('rules.recovery_time')
                            ->label(__('filament.labels.recovery_time')),
                        Infolists\Components\TextEntry::make('rules.load_type')
                            ->label(__('filament.labels.load_type')),
                        Infolists\Components\TextEntry::make('rules.charges')
                            ->label(__('filament.labels.charges'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.speed_intensity')
                            ->label(__('filament.labels.speed_intensity'))
                            ->placeholder('—'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('filament.sections.metabolic_parameters'))
                    ->icon('heroicon-o-fire')
                    ->schema([
                        Infolists\Components\TextEntry::make('rules.mets')
                            ->label(__('filament.labels.mets'))
                            ->badge()
                            ->color('warning')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.duration')
                            ->label(__('filament.labels.duration'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.rpe')
                            ->label(__('filament.labels.rpe'))
                            ->badge()
                            ->color(fn (?int $state): string => match (true) {
                                $state === null => 'gray',
                                $state <= 3 => 'success',
                                $state <= 6 => 'warning',
                                $state <= 8 => 'warning',
                                default => 'danger',
                            })
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.load_ua')
                            ->label(__('filament.labels.load_ua'))
                            ->badge()
                            ->color('info')
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.impact')
                            ->label(__('filament.labels.impact'))
                            ->badge()
                            ->color(fn (?int $state): string => match (true) {
                                $state === null => 'gray',
                                $state <= 2 => 'success',
                                $state <= 3 => 'warning',
                                default => 'danger',
                            })
                            ->placeholder('—'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('filament.sections.recovery_supercomp'))
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Infolists\Components\TextEntry::make('rules.sleep_requirement')
                            ->label(__('filament.labels.sleep_requirement'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.hydration')
                            ->label(__('filament.labels.hydration'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.freshness_24h')
                            ->label(__('filament.labels.freshness_24h'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.freshness_48h')
                            ->label(__('filament.labels.freshness_48h'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.freshness_72h')
                            ->label(__('filament.labels.freshness_72h'))
                            ->placeholder('—'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('filament.sections.performance_science'))
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        Infolists\Components\TextEntry::make('rules.supercomp_window')
                            ->label(__('filament.labels.supercomp_window'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.gain_prediction')
                            ->label(__('filament.labels.gain_prediction'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.injury_risk')
                            ->label(__('filament.labels.injury_risk'))
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'Nul' => 'gray',
                                'Très Faible', 'Tres Faible' => 'success',
                                'Faible' => 'success',
                                'Moyen' => 'warning',
                                'Élevé', 'Eleve' => 'danger',
                                'Très Élevé', 'Tres Eleve' => 'danger',
                                'Critique' => 'danger',
                                default => 'gray',
                            })
                            ->placeholder('—'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('filament.sections.load_thresholds'))
                    ->icon('heroicon-o-exclamation-triangle')
                    ->schema([
                        Infolists\Components\TextEntry::make('rules.daily_alert_threshold')
                            ->label(__('filament.labels.daily_alert_threshold'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.weekly_alert_threshold')
                            ->label(__('filament.labels.weekly_alert_threshold'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.elastic_recoil')
                            ->label(__('filament.labels.elastic_recoil'))
                            ->placeholder('—'),
                        Infolists\Components\TextEntry::make('rules.cfa')
                            ->label(__('filament.labels.cfa'))
                            ->placeholder('—'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('filament.sections.associated_themes'))
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('playerProfiles')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('group')
                                    ->label(__('filament.labels.group')),
                                Infolists\Components\TextEntry::make('pivot.percentage')
                                    ->label(__('filament.labels.weight'))
                                    ->suffix('%'),
                            ])
                            ->columns(3),
                    ])
                    ->visible(fn ($record) => $record->playerProfiles()->count() > 0),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorkoutThemes::route('/'),
            'create' => Pages\CreateWorkoutTheme::route('/create'),
            'edit' => Pages\EditWorkoutTheme::route('/{record}/edit'),
            'view' => Pages\ViewWorkoutTheme::route('/{record}'),
        ];
    }
}
