<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

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
                            ->schema([
                                Forms\Components\TextInput::make('name')->required(),
                                Forms\Components\TextInput::make('email')->email()->required(),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->required(fn (string $context): bool => $context === 'create')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Sport Profile')
                            ->relationship('profile')
                            ->schema([
                                Forms\Components\Toggle::make('is_onboarding_complete')->label('Onboarding Complete'),
                                Forms\Components\Select::make('discipline')->options(fn() => \App\Models\OnboardingOption::where('type', 'discipline')->pluck('name_fr', 'key')),
                                Forms\Components\Select::make('position')->options(fn() => \App\Models\PlayerProfile::all()->pluck('name', 'name')),
                                Forms\Components\Toggle::make('in_club')->label('In a Club?'),
                                Forms\Components\Select::make('level')->options(fn() => \App\Models\OnboardingOption::where('type', 'level')->pluck('name_fr', 'key')),
                                Forms\Components\Select::make('training_location')->options(fn() => \App\Models\OnboardingOption::where('type', 'location')->pluck('name_fr', 'key')),
                                Forms\Components\TagsInput::make('training_days')->label('Training Days'),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Personal & Nutrition')
                            ->relationship('profile')
                            ->schema([
                                Forms\Components\TextInput::make('age')->numeric(),
                                Forms\Components\TextInput::make('weight')->numeric()->suffix('kg'),
                                Forms\Components\TextInput::make('height')->numeric()->suffix('cm'),
                                Forms\Components\TextInput::make('ideal_weight')->numeric()->suffix('kg'),
                                Forms\Components\Select::make('gender')->options(['HOMME' => 'Homme', 'FEMME' => 'Femme']),
                                Forms\Components\Select::make('goal')->options(fn() => \App\Models\OnboardingOption::where('type', 'goal')->pluck('name_fr', 'key')),
                                Forms\Components\Select::make('morphology')->options(fn() => \App\Models\OnboardingOption::where('type', 'morphology')->pluck('name_fr', 'key')),
                                Forms\Components\Toggle::make('is_vegetarian'),
                            ])->columns(2),

                        Forms\Components\Tabs\Tab::make('Medical')
                            ->relationship('profile')
                            ->schema([
                                Forms\Components\Toggle::make('has_injury'),
                                Forms\Components\Select::make('injury_location')->options(fn() => \App\Models\OnboardingOption::where('type', 'injury_location')->pluck('name_fr', 'key')),
                                Forms\Components\Toggle::make('has_diabetes'),
                                Forms\Components\Toggle::make('takes_medication'),
                                Forms\Components\TagsInput::make('family_history')->columnSpanFull(),
                                Forms\Components\TagsInput::make('medical_history')->columnSpanFull(),
                            ])->columns(2),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\IconColumn::make('profile.is_onboarding_complete')
                    ->label('Onboarded')
                    ->boolean(),
                Tables\Columns\TextColumn::make('profile.position')->label('Position')->searchable(),
                Tables\Columns\TextColumn::make('profile.goal')->label('Goal'),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
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
