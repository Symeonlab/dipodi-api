<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\WorkoutSessionResource\Pages;
use App\Models\WorkoutSession;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Model; // <-- Added this import

class WorkoutSessionResource extends Resource
{
    protected static ?string $model = WorkoutSession::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'User Data';

    // Make this page read-only
    public static function canCreate(): bool { return false; }
    public static function canEdit(Model $record): bool { return false; }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('day')->sortable(),
                Tables\Columns\TextColumn::make('theme')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user')->relationship('user', 'name')
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    // This creates the "View" page to see the full workout
    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Session Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name'),
                        Infolists\Components\TextEntry::make('day'),
                        Infolists\Components\TextEntry::make('theme'),
                    ])->columns(3),
                Infolists\Components\Section::make('Workout Steps')
                    ->schema([
                        Infolists\Components\TextEntry::make('warmup'),
                        Infolists\Components\RepeatableEntry::make('exercises')
                            ->schema([
                                Infolists\Components\TextEntry::make('name'),
                                Infolists\Components\TextEntry::make('sets'), // <-- FIX: Was 'InLofists'
                                Infolists\Components\TextEntry::make('reps'),
                                Infolists\Components\TextEntry::make('recovery'),
                                Infolists\Components\TextEntry::make('video_url')
                                    ->url(fn (?string $state): ?string => $state)
                                    ->openUrlInNewTab(),
                            ])->columns(5),
                        Infolists\Components\TextEntry::make('finisher'),
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
        return [
            'index' => Pages\ListWorkoutSessions::route('/'),
            'view' => Pages\ViewWorkoutSession::route('/{record}'), // This line will now work
        ];
    }
}
