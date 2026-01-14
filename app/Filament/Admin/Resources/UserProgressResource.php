<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserProgressResource\Pages;
use App\Models\UserProgress;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class UserProgressResource extends Resource
{
    protected static ?string $model = UserProgress::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'User Data';

    // This form is for editing an existing entry
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('date')->required(),
                Forms\Components\TextInput::make('weight')->numeric()->suffix('kg'),
                Forms\Components\TextInput::make('waist')->numeric()->suffix('cm'),
                Forms\Components\TextInput::make('chest')->numeric()->suffix('cm'),
                Forms\Components\TextInput::make('hips')->numeric()->suffix('cm'),
                Forms\Components\TextInput::make('mood'),
                Forms\Components\Textarea::make('notes')->columnSpanFull(),
                Forms\Components\TextInput::make('workout_completed'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('date')->date()->sortable(),
                Tables\Columns\TextColumn::make('weight')->suffix(' kg')->sortable(),
                Tables\Columns\TextColumn::make('waist')->suffix(' cm')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('chest')->suffix(' cm')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('hips')->suffix(' cm')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mood')->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user')->relationship('user', 'name'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]);
    }

    // This is the "View" page
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('user.name'),
                Infolists\Components\TextEntry::make('date')->date(),
                Infolists\Components\TextEntry::make('weight')->suffix(' kg'),
                Infolists\Components\TextEntry::make('waist')->suffix(' cm'),
                Infolists\Components\TextEntry::make('chest')->suffix(' cm'),
                Infolists\Components\TextEntry::make('hips')->suffix(' cm'),
                Infolists\Components\TextEntry::make('mood'),
                Infolists\Components\TextEntry::make('workout_completed'),
                Infolists\Components\TextEntry::make('notes')->columnSpanFull(),
            ])->columns(2);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserProgresses::route('/'),
            'create' => Pages\CreateUserProgress::route('/create'),
            'edit' => Pages\EditUserProgress::route('/{record}/edit'),
            'view' => Pages\ViewUserProgress::route('/{record}'),
        ];
    }
}
