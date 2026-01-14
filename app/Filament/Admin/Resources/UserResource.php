<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Filament\Admin\Resources\UserResource\RelationManagers; // <-- Import RelationManagers
use App\Models\User;
use App\Models\OnboardingOption;
use App\Models\PlayerProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('User Details')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Account')
                            ->icon('heroicon-o-user-circle')
                            ->schema([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('email')->email()->required(),
                                Forms\Components\Select::make('role')
                                    ->options([
                                        'admin' => 'Admin',
                                        'coach' => 'Coach',
                                        'manager' => 'Manager',
                                        'user' => 'User',
                                    ])
                                    ->required()->default('user'),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->columnSpanFull(),

                                // --- FIX: We must use relationship() on a Section ---
                                Forms\Components\Section::make('Profile Status')
                                    ->relationship('profile')
                                    ->schema([
                                        Forms\Components\Toggle::make('is_onboarding_complete')
                                            ->label('Onboarding Complete')
                                            ->helperText('If this is off, the user will be forced into the onboarding flow on the app.'),
                                    ])
                                // --- END FIX ---
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Sport Profile')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                // --- FIX: We wrap the fields in a Section ---
                                Forms\Components\Section::make('Sport Details')
                                    ->relationship('profile') // <-- The relationship call is moved here
                                    ->schema([
                                        Forms\Components\Select::make('discipline')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'discipline')->pluck('name_fr', 'key'))
                                            ->searchable(),
                                        Forms\Components\Select::make('position')
                                            ->label('Player Position')
                                            ->options(fn() => \App\Models\PlayerProfile::all()->pluck('name', 'name'))
                                            ->searchable(),
                                        Forms\Components\Toggle::make('in_club')->label('In a Club?'),
                                        Forms\Components\Select::make('match_day')->options([
                                            'AUCUN' => 'Aucun', 'LUNDI' => 'Lundi', 'MARDI' => 'Mardi', 'MERCREDI' => 'Mercredi', 'JEUDI' => 'Jeudi', 'VENDREDI' => 'Vendredi', 'SAMEDI' => 'Samedi', 'DIMANCHE' => 'Dimanche'
                                        ]),
                                        Forms\Components\TagsInput::make('training_days')->label('Training Days'),
                                        Forms\Components\Select::make('level')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'level')->pluck('name_fr', 'key')),
                                        Forms\Components\Select::make('training_location')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'location')->pluck('name_fr', 'key')),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Personal & Nutrition')
                            ->icon('heroicon-o-heart')
                            ->schema([
                                // --- FIX: We wrap the fields in a Section ---
                                Forms\Components\Section::make('Personal & Nutrition Details')
                                    ->relationship('profile') // <-- The relationship call is moved here
                                    ->schema([
                                        Forms\Components\TextInput::make('age')->numeric(),
                                        Forms\Components\TextInput::make('weight')->numeric()->suffix('kg'),
                                        Forms\Components\TextInput::make('height')->numeric()->suffix('cm'),
                                        Forms\Components\TextInput::make('ideal_weight')->numeric()->suffix('kg'),
                                        Forms\Components\Select::make('gender')->options(['HOMME' => 'Homme', 'FEMME' => 'Femme']),
                                        Forms\Components\Select::make('goal')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'goal')->pluck('name_fr', 'key')),
                                        Forms\Components\Select::make('morphology')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'morphology')->pluck('name_fr', 'key')),
                                        Forms\Components\Toggle::make('is_vegetarian'),
                                        Forms\Components\TagsInput::make('breakfast_preferences'),
                                        Forms\Components\TagsInput::make('bad_habits'),
                                    ])->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Medical')
                            ->icon('heroicon-o-clipboard-document-list')
                            ->schema([
                                // --- FIX: We wrap the fields in a Section ---
                                Forms\Components\Section::make('Medical Details')
                                    ->relationship('profile') // <-- The relationship call is moved here
                                    ->schema([
                                        Forms\Components\Toggle::make('has_injury'),
                                        Forms\Components\Select::make('injury_location')
                                            ->options(fn() => \App\Models\OnboardingOption::where('type', 'injury_location')->pluck('name_fr', 'key')),
                                        Forms\Components\Toggle::make('has_diabetes'),
                                        Forms\Components\Toggle::make('takes_medication'),
                                        Forms\Components\Select::make('hormonal_issues')->options([
                                            'OUI' => 'Oui', 'NON' => 'Non', 'JE NE SAIS PAS' => 'Je ne sais pas'
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
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),

                // This column shows the user's role
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'coach' => 'warning',
                        'manager' => 'info',
                        'user' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),

                Tables\Columns\IconColumn::make('profile.is_onboarding_complete')
                    ->label('Onboarded')
                    ->boolean(),
                Tables\Columns\TextColumn::make('profile.position')
                    ->label('Position')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('profile.goal')
                    ->label('Goal')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'admin' => 'Admin',
                        'coach' => 'Coach',
                        'manager' => 'Manager',
                        'user' => 'User',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * This function loads the "Reminder Settings" and "Favorite Exercises"
     * sections at the bottom of the "Edit User" page.
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\ReminderSettingsRelationManager::class,
            RelationManagers\FavoriteExercisesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
