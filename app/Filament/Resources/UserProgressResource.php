<?php
namespace App\Filament\Resources;
use App\Filament\Resources\UserProgressResource\Pages;
use App\Models\UserProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserProgressResource extends Resource
{
    protected static ?string $model = UserProgress::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    public static function form(Form $form): Form
    {
        return $form->schema([]); // We won't create progress from the admin
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\TextColumn::make('weight')->suffix(' kg'),
                Tables\Columns\TextColumn::make('mood'),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user')->relationship('user', 'name'),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserProgress::route('/'),
        ];
    }
}
