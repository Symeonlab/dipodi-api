<?php

namespace App\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReminderSettingsRelationManager extends RelationManager
{
    protected static string $relationship = 'reminderSettings';
    protected static ?string $title = 'Reminder Settings';

    // This is a HasOne relationship, so we use a Form and Table
    // to edit the single related record.

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('breakfast_enabled')->required(),
                Forms\Components\TimePicker::make('breakfast_time')->required()->seconds(false),

                Forms\Components\Toggle::make('lunch_enabled')->required(),
                Forms\Components\TimePicker::make('lunch_time')->required()->seconds(false),

                Forms\Components\Toggle::make('dinner_enabled')->required(),
                Forms\Components\TimePicker::make('dinner_time')->required()->seconds(false),

                Forms\Components\Toggle::make('workout_enabled')->required(),
                Forms\Components\TimePicker::make('workout_time')->required()->seconds(false),
            ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id') // Not important, we just need the actions
            ->columns([
                Tables\Columns\ToggleColumn::make('breakfast_enabled'),
                Tables\Columns\TextColumn::make('breakfast_time'),
                Tables\Columns\ToggleColumn::make('lunch_enabled'),
                Tables\Columns\TextColumn::make('lunch_time'),
                Tables\Columns\ToggleColumn::make('dinner_enabled'),
                Tables\Columns\TextColumn::make('dinner_time'),
                Tables\Columns\ToggleColumn::make('workout_enabled'),
                Tables\Columns\TextColumn::make('workout_time'),
            ])
            ->headerActions([
                // This button creates the settings if they don't exist
                Tables\Actions\Action::make('create')
                    ->label('Create Default Settings')
                    ->action(fn ($livewire) => $livewire->ownerRecord->reminderSettings()->firstOrCreate())
                    ->hidden(fn ($livewire) => $livewire->ownerRecord->reminderSettings()->exists()),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }
}
