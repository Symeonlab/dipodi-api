<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\Models\OnboardingOption;
use App\Models\PlayerProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.users');
    }

    public static function getModelLabel(): string
    {
        return __('filament.labels.name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.users');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email', 'role'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make(__('filament.sections.account'))
                    ->tabs([
                        Forms\Components\Tabs\Tab::make(__('filament.sections.account'))
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('email')->email()->required(),
                                Forms\Components\Select::make('role')
                                    ->options([
                                        'admin' => __('filament.roles.admin'),
                                        'coach' => __('filament.roles.coach'),
                                        'manager' => __('filament.roles.manager'),
                                        'user' => __('filament.roles.user'),
                                    ])
                                    ->required()->default('user'),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->columnSpanFull(),

                                // --- FIX: We must use relationship() on a Section ---
                                Forms\Components\Section::make(__('filament.sections.profile_status'))
                                    ->relationship('profile')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_onboarding_complete')
                                            ->label(__('filament.labels.onboarding_complete'))
                                            ->helperText(__('filament.helper.onboarding_complete')),
                                    ])
                                // --- END FIX ---
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make(__('filament.sections.sport_profile'))
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                // --- FIX: We wrap the fields in a Section ---
                                Forms\Components\Section::make(__('filament.sections.sport_details'))
                                    ->relationship('profile') // <-- The relationship call is moved here
                                    ->schema([
                                        Forms\Components\Select::make('discipline')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'discipline')->pluck('name_fr', 'key'))
                                            ->searchable(),
                                        Forms\Components\Select::make('position')
                                            ->label(__('filament.labels.player_position'))
                                            ->options(fn() => \App\Models\PlayerProfile::all()->pluck('name', 'name'))
                                            ->searchable(),
                                        Forms\Components\Toggle::make('in_club')->label(__('filament.labels.in_club')),
                                        Forms\Components\Select::make('match_day')->options([
                                            'AUCUN' => __('filament.days.none'), 'LUNDI' => __('filament.days.monday'), 'MARDI' => __('filament.days.tuesday'), 'MERCREDI' => __('filament.days.wednesday'), 'JEUDI' => __('filament.days.thursday'), 'VENDREDI' => __('filament.days.friday'), 'SAMEDI' => __('filament.days.saturday'), 'DIMANCHE' => __('filament.days.sunday')
                                        ]),
                                        Forms\Components\TagsInput::make('training_days')->label(__('filament.labels.training_days')),
                                        Forms\Components\Select::make('level')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'level')->pluck('name_fr', 'key')),
                                        Forms\Components\Select::make('training_location')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'location')->pluck('name_fr', 'key')),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('filament.sections.personal_nutrition'))
                            ->icon('heroicon-o-heart')
                            ->schema([
                                // --- FIX: We wrap the fields in a Section ---
                                Forms\Components\Section::make(__('filament.sections.personal_nutrition_details'))
                                    ->relationship('profile') // <-- The relationship call is moved here
                                    ->schema([
                                        Forms\Components\TextInput::make('age')->numeric(),
                                        Forms\Components\TextInput::make('weight')->numeric()->suffix('kg'),
                                        Forms\Components\TextInput::make('height')->numeric()->suffix('cm'),
                                        Forms\Components\TextInput::make('ideal_weight')->numeric()->suffix('kg'),
                                        Forms\Components\Select::make('gender')->options(['HOMME' => __('filament.gender.male'), 'FEMME' => __('filament.gender.female')]),
                                        Forms\Components\Select::make('goal')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'goal')->pluck('name_fr', 'key')),
                                        Forms\Components\Select::make('morphology')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'morphology')->pluck('name_fr', 'key')),
                                        Forms\Components\Toggle::make('is_vegetarian'),
                                        Forms\Components\TagsInput::make('breakfast_preferences'),
                                        Forms\Components\TagsInput::make('bad_habits'),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make(__('filament.sections.medical'))
                            ->icon('heroicon-o-clipboard-document-list')
                            ->schema([
                                // --- FIX: We wrap the fields in a Section ---
                                Forms\Components\Section::make(__('filament.sections.medical_details'))
                                    ->relationship('profile') // <-- The relationship call is moved here
                                    ->schema([
                                        Forms\Components\Toggle::make('has_injury'),
                                        Forms\Components\Select::make('injury_location')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'injury_location')->pluck('name_fr', 'key')),
                                        Forms\Components\Toggle::make('has_diabetes'),
                                        Forms\Components\Toggle::make('takes_medication'),
                                        Forms\Components\Select::make('hormonal_issues')->options([
                                            'OUI' => __('filament.hormonal.yes'), 'NON' => __('filament.hormonal.no'), 'JE NE SAIS PAS' => __('filament.hormonal.unknown')
                                        ]),
                                        Forms\Components\TagsInput::make('family_history')->columnSpanFull(),
                                        Forms\Components\TagsInput::make('medical_history')->columnSpanFull(),
                                    ])->columns(2),
                            ]),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->copyMessage(__('filament.actions.email_copied')),
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'coach' => 'warning',
                        'manager' => 'info',
                        'user' => 'success',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'admin' => 'heroicon-o-shield-check',
                        'coach' => 'heroicon-o-academic-cap',
                        'manager' => 'heroicon-o-briefcase',
                        'user' => 'heroicon-o-user',
                        default => 'heroicon-o-user',
                    })
                    ->sortable(),
                Tables\Columns\IconColumn::make('profile.is_onboarding_complete')
                    ->label(__('filament.labels.onboarded'))
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('profile.discipline')
                    ->label(__('filament.labels.discipline'))
                    ->badge()
                    ->color('info')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('profile.position')
                    ->label(__('filament.labels.position'))
                    ->searchable()
                    ->badge()
                    ->color('purple'),
                Tables\Columns\TextColumn::make('profile.goal')
                    ->label(__('filament.labels.goal'))
                    ->badge()
                    ->color('warning')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('profile.gender')
                    ->label(__('filament.labels.gender'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'HOMME' => 'info',
                        'FEMME' => 'pink',
                        default => 'gray',
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.joined'))
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->since()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => __('filament.roles.admin'),
                        'coach' => __('filament.roles.coach'),
                        'manager' => __('filament.roles.manager'),
                        'user' => __('filament.roles.user'),
                    ])
                    ->multiple()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('onboarded')
                    ->label(__('filament.filters.onboarding_status'))
                    ->queries(
                        true: fn (Builder $query) => $query->whereHas('profile', fn ($q) => $q->where('is_onboarding_complete', true)),
                        false: fn (Builder $query) => $query->whereHas('profile', fn ($q) => $q->where('is_onboarding_complete', false)),
                        blank: fn (Builder $query) => $query,
                    )
                    ->trueLabel(__('filament.filters.completed'))
                    ->falseLabel(__('filament.filters.not_completed')),
                Tables\Filters\SelectFilter::make('discipline')
                    ->label(__('filament.labels.discipline'))
                    ->options(fn () => \App\Models\UserProfile::query()
                        ->whereNotNull('discipline')
                        ->distinct()
                        ->pluck('discipline', 'discipline')
                        ->filter()
                        ->toArray())
                    ->query(fn (Builder $query, array $data): Builder =>
                        $data['value'] ? $query->whereHas('profile', fn ($q) => $q->where('discipline', $data['value'])) : $query
                    )
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('gender')
                    ->label(__('filament.labels.gender'))
                    ->options([
                        'HOMME' => __('filament.gender.male'),
                        'FEMME' => __('filament.gender.female'),
                    ])
                    ->query(fn (Builder $query, array $data): Builder =>
                        $data['value'] ? $query->whereHas('profile', fn ($q) => $q->where('gender', $data['value'])) : $query
                    ),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('joined_from')
                            ->label(__('filament.filters.joined_from')),
                        Forms\Components\DatePicker::make('joined_until')
                            ->label(__('filament.filters.joined_until')),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['joined_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['joined_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->filtersFormColumns(2)
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.users'))
            ->emptyStateDescription(__('filament.empty.users_desc'))
            ->emptyStateIcon('heroicon-o-users');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.account_info'))
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('email')
                            ->icon('heroicon-o-envelope')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('role')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'admin' => 'danger',
                                'coach' => 'warning',
                                'manager' => 'info',
                                'user' => 'success',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label(__('filament.labels.member_since'))
                            ->dateTime('F j, Y'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make(__('filament.sections.profile_status'))
                    ->icon('heroicon-o-check-badge')
                    ->schema([
                        Infolists\Components\IconEntry::make('profile.is_onboarding_complete')
                            ->label(__('filament.labels.onboarding_complete'))
                            ->boolean(),
                    ]),

                Infolists\Components\Section::make(__('filament.sections.sport_profile'))
                    ->icon('heroicon-o-trophy')
                    ->schema([
                        Infolists\Components\TextEntry::make('profile.discipline')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('profile.position')
                            ->badge()
                            ->color('purple'),
                        Infolists\Components\TextEntry::make('profile.level')
                            ->badge(),
                        Infolists\Components\IconEntry::make('profile.in_club')
                            ->label(__('filament.labels.in_club_label'))
                            ->boolean(),
                        Infolists\Components\TextEntry::make('profile.match_day')
                            ->badge()
                            ->color('warning'),
                        Infolists\Components\TextEntry::make('profile.training_days')
                            ->badge()
                            ->separator(','),
                        Infolists\Components\TextEntry::make('profile.training_location')
                            ->badge()
                            ->color('success'),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Infolists\Components\Section::make(__('filament.sections.personal_nutrition'))
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Infolists\Components\TextEntry::make('profile.age')
                            ->suffix(' years'),
                        Infolists\Components\TextEntry::make('profile.gender')
                            ->badge()
                            ->color(fn (?string $state): string => $state === 'HOMME' ? 'info' : 'pink'),
                        Infolists\Components\TextEntry::make('profile.weight')
                            ->suffix(' kg'),
                        Infolists\Components\TextEntry::make('profile.height')
                            ->suffix(' cm'),
                        Infolists\Components\TextEntry::make('profile.ideal_weight')
                            ->suffix(' kg'),
                        Infolists\Components\TextEntry::make('profile.goal')
                            ->badge()
                            ->color('warning'),
                        Infolists\Components\TextEntry::make('profile.morphology')
                            ->badge(),
                        Infolists\Components\IconEntry::make('profile.is_vegetarian')
                            ->label(__('filament.labels.vegetarian'))
                            ->boolean(),
                    ])
                    ->columns(4)
                    ->collapsible(),

                Infolists\Components\Section::make(__('filament.sections.medical_info'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Infolists\Components\IconEntry::make('profile.has_injury')
                            ->label(__('filament.labels.has_injury'))
                            ->boolean(),
                        Infolists\Components\TextEntry::make('profile.injury_location')
                            ->badge()
                            ->color('danger'),
                        Infolists\Components\IconEntry::make('profile.has_diabetes')
                            ->label(__('filament.labels.has_diabetes'))
                            ->boolean(),
                        Infolists\Components\IconEntry::make('profile.takes_medication')
                            ->label(__('filament.labels.takes_medication'))
                            ->boolean(),
                        Infolists\Components\TextEntry::make('profile.hormonal_issues')
                            ->badge(),
                        Infolists\Components\TextEntry::make('profile.family_history')
                            ->badge()
                            ->separator(','),
                        Infolists\Components\TextEntry::make('profile.medical_history')
                            ->badge()
                            ->separator(','),
                    ])
                    ->columns(4)
                    ->collapsible(),

                Infolists\Components\Section::make(__('filament.sections.feedback_summary'))
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([
                        Infolists\Components\TextEntry::make('feedback_total_sessions')
                            ->label(__('filament.labels.total_sessions'))
                            ->state(fn ($record) => $record->feedbackSessions()->count())
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('feedback_completed_sessions')
                            ->label(__('filament.labels.completed_sessions'))
                            ->state(fn ($record) => $record->feedbackSessions()->where('status', 'completed')->count())
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('feedback_average_score')
                            ->label(__('filament.labels.average_score'))
                            ->state(function ($record) {
                                $avg = $record->feedbackSessions()
                                    ->where('status', 'completed')
                                    ->whereNotNull('average_score')
                                    ->avg('average_score');
                                return $avg ? number_format($avg, 1) . '/10' : 'N/A';
                            })
                            ->badge()
                            ->color(fn ($state) => match (true) {
                                str_contains($state, 'N/A') => 'gray',
                                (float)$state >= 7 => 'success',
                                (float)$state >= 5 => 'warning',
                                default => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('feedback_total_answers')
                            ->label(__('filament.labels.total_answers'))
                            ->state(fn ($record) => $record->feedbackAnswers()->count())
                            ->badge()
                            ->color('purple'),
                    ])
                    ->columns(4)
                    ->collapsible(),

                Infolists\Components\Section::make(__('filament.sections.health_summary'))
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Infolists\Components\TextEntry::make('health_total_sessions')
                            ->label(__('filament.labels.total_assessments'))
                            ->state(fn ($record) => $record->healthAssessmentSessions()->count())
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('health_completed_sessions')
                            ->label(__('filament.labels.completed_sessions'))
                            ->state(fn ($record) => $record->healthAssessmentSessions()->where('status', 'completed')->count())
                            ->badge()
                            ->color('success'),
                        Infolists\Components\TextEntry::make('health_concerns_count')
                            ->label(__('filament.labels.total_concerns'))
                            ->state(function ($record) {
                                return $record->healthAssessmentAnswers()
                                    ->whereIn('answer_value', ['oui', 'yes', '1', 'true'])
                                    ->count();
                            })
                            ->badge()
                            ->color(fn ($state) => $state > 20 ? 'danger' : ($state > 10 ? 'warning' : 'success')),
                        Infolists\Components\TextEntry::make('health_critical_concerns')
                            ->label(__('filament.labels.critical_concerns'))
                            ->state(function ($record) {
                                return $record->healthAssessmentAnswers()
                                    ->whereIn('answer_value', ['oui', 'yes', '1', 'true'])
                                    ->whereHas('question', fn ($q) => $q->where('is_critical', true))
                                    ->count();
                            })
                            ->badge()
                            ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                    ])
                    ->columns(4)
                    ->collapsible(),
            ]);
    }

    /**
     * This function loads the "Reminder Settings", "Favorite Exercises",
     * "Feedback Sessions", and "Health Assessment Sessions" sections at the bottom of the "Edit User" page.
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\ReminderSettingsRelationManager::class,
            RelationManagers\FavoriteExercisesRelationManager::class,
            RelationManagers\FeedbackSessionsRelationManager::class,
            RelationManagers\HealthAssessmentSessionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
