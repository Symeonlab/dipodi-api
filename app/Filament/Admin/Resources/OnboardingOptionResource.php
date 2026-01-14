<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OnboardingOptionResource\Pages;
use App\Models\OnboardingOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OnboardingOptionResource extends Resource
{
    protected static ?string $model = OnboardingOption::class;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?string $navigationLabel = 'Onboarding Options';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->helperText('e.g., "discipline", "goal", "level"'),
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->helperText('The unique key used by the app, e.g., "goal.lose_weight"'),
                Forms\Components\TextInput::make('name_en')->label('Name (English)')->required(),
                Forms\Components\TextInput::make('name_fr')->label('Name (Français)')->required(),
                Forms\Components\TextInput::make('name_ar')->label('Name (العربية)')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label('English'),
                Tables\Columns\TextColumn::make('name_fr')
                    ->label('Français'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options(OnboardingOption::query()->distinct()->pluck('type', 'type')),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOnboardingOptions::route('/'),
            'create' => Pages\CreateOnboardingOption::route('/create'),
            'edit' => Pages\EditOnboardingOption::route('/{record}/edit'),
        ];
    }
}
