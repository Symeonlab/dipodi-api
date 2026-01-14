<?php

namespace App\Filament\Admin\Resources; // Using your 'Admin' panel path

use App\Filament\Admin\Resources\NutritionAdviceResource\Pages; // Using your 'Admin' panel path
use App\Models\NutritionAdvice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NutritionAdviceResource extends Resource
{
    protected static ?string $model = NutritionAdvice::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('condition_name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TagsInput::make('foods_to_avoid')
                    ->label('Foods to Avoid'),
                Forms\Components\TagsInput::make('foods_to_eat')
                    ->label('Foods to Eat'),
                Forms\Components\Textarea::make('prophetic_advice_fr')
                    ->label('Prophetic Advice (FR)')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('prophetic_advice_en')
                    ->label('Prophetic Advice (EN)')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('prophetic_advice_ar')
                    ->label('Prophetic Advice (AR)')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('condition_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        // This is the function that had the error
        return [
            'index' => Pages\ListNutritionAdvice::route('/'),
            'create' => Pages\CreateNutritionAdvice::route('/create'),
            'edit' => Pages\EditNutritionAdvice::route('/{record}/edit'),
            'view' => Pages\ViewNutritionAdvice::route('/{record}'), // <-- Ensure this line is there
        ];
    }
}
