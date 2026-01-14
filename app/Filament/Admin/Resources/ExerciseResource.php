<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ExerciseResource\Pages;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;

    // This sets the icon in the sidebar
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    // This groups it with your other content
    protected static ?string $navigationGroup = 'Content Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('category')
                    ->required()
                    ->maxLength(255)
                    ->helperText('e.g., KINE RENFORCEMENT, MUSCULATION, CARDIO EN SALLE'),
                Forms\Components\TextInput::make('sub_category')
                    ->maxLength(255)
                    ->helperText('e.g., QUADRICEPS, BRAS, SPRINT EN COTE'),
                Forms\Components\TextInput::make('video_url')
                    ->url()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('met_value')
                    ->numeric()
                    ->label('MET Value (for calorie calculation)'),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable()
                    ->badge(),
                Tables\Columns\TextColumn::make('sub_category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('favoritedByUsers_count')
                    ->label('Favorites')
                    ->counts('favoritedByUsers')
                    ->sortable(),
                Tables\Columns\TextColumn::make('met_value')
                    ->sortable(),
                Tables\Columns\IconColumn::make('video_url')
                    ->label('Has Video')
                    ->boolean(),
            ])
            ->filters([
                // This allows you to filter by "KINE", "MUSCULATION", etc.
                Tables\Filters\SelectFilter::make('category')
                    ->options(Exercise::query()->distinct()->pluck('category', 'category'))
                    ->multiple(),
                Tables\Filters\SelectFilter::make('sub_category')
                    ->options(Exercise::query()->distinct()->pluck('sub_category', 'sub_category'))
                    ->multiple()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // This registers the List, Create, Edit, and View pages
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExercises::route('/'),
            'create' => Pages\CreateExercise::route('/create'),
            'edit' => Pages\EditExercise::route('/{record}/edit'),
            'view' => Pages\ViewExercise::route('/{record}'),
        ];
    }
}
