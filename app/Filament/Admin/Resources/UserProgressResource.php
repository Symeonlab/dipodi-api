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
use Illuminate\Database\Eloquent\Builder;

class UserProgressResource extends Resource
{
    protected static ?string $model = UserProgress::class;
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.user_progress');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.user_progress');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['user.name', 'user.email', 'mood', 'workout_completed'];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('User & Date')
                    ->icon('heroicon-o-calendar')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Forms\Components\DatePicker::make('date')
                            ->label('Log Date')
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Body Measurements')
                    ->description('Track body metrics from the mobile app')
                    ->icon('heroicon-o-scale')
                    ->schema([
                        Forms\Components\TextInput::make('weight')
                            ->label('Weight')
                            ->numeric()
                            ->suffix('kg')
                            ->minValue(30)
                            ->maxValue(300),
                        Forms\Components\TextInput::make('waist')
                            ->label('Waist')
                            ->numeric()
                            ->suffix('cm')
                            ->minValue(30)
                            ->maxValue(200),
                        Forms\Components\TextInput::make('chest')
                            ->label('Chest')
                            ->numeric()
                            ->suffix('cm')
                            ->minValue(30)
                            ->maxValue(200),
                        Forms\Components\TextInput::make('hips')
                            ->label('Hips')
                            ->numeric()
                            ->suffix('cm')
                            ->minValue(30)
                            ->maxValue(200),
                    ])
                    ->columns(4),

                Forms\Components\Section::make('Workout & Notes')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\TextInput::make('workout_completed')
                            ->label('Workout Completed')
                            ->placeholder('e.g., Full body, Cardio, Kine'),
                        Forms\Components\Select::make('mood')
                            ->label('Mood')
                            ->options([
                                'great' => 'Great',
                                'good' => 'Good',
                                'okay' => 'Okay',
                                'tired' => 'Tired',
                                'bad' => 'Bad',
                            ])
                            ->native(false),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('weight')
                    ->label('Weight')
                    ->suffix(' kg')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color('info'),
                Tables\Columns\TextColumn::make('waist')
                    ->label('Waist')
                    ->suffix(' cm')
                    ->numeric()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('chest')
                    ->label('Chest')
                    ->suffix(' cm')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('hips')
                    ->label('Hips')
                    ->suffix(' cm')
                    ->numeric()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('mood')
                    ->label('Mood')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'great' => 'success',
                        'good' => 'info',
                        'okay' => 'warning',
                        'tired' => 'gray',
                        'bad' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('workout_completed')
                    ->label('Workout')
                    ->badge()
                    ->color('purple')
                    ->limit(20)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Logged')
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('mood')
                    ->options([
                        'great' => 'Great',
                        'good' => 'Good',
                        'okay' => 'Okay',
                        'tired' => 'Tired',
                        'bad' => 'Bad',
                    ]),
                Tables\Filters\Filter::make('has_weight')
                    ->label('Has Weight')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('weight')),
                Tables\Filters\Filter::make('date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['from'], fn (Builder $query, $date) => $query->whereDate('date', '>=', $date))
                            ->when($data['until'], fn (Builder $query, $date) => $query->whereDate('date', '<=', $date));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csv = "User,Date,Weight,Waist,Chest,Hips,Mood,Workout,Notes\n";
                            foreach ($records as $record) {
                                $notes = str_replace(["\n", "\r", '"'], [' ', ' ', '""'], $record->notes ?? '');
                                $csv .= "\"{$record->user?->name}\",\"{$record->date}\",\"{$record->weight}\",\"{$record->waist}\",\"{$record->chest}\",\"{$record->hips}\",\"{$record->mood}\",\"{$record->workout_completed}\",\"{$notes}\"\n";
                            }
                            return response()->streamDownload(fn () => print($csv), 'user-progress-export.csv');
                        }),
                ]),
            ])
            ->emptyStateHeading('No progress logs yet')
            ->emptyStateDescription('User progress logs from the mobile app will appear here.')
            ->emptyStateIcon('heroicon-o-chart-bar');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('User & Date')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('User'),
                        Infolists\Components\TextEntry::make('user.email')
                            ->label('Email'),
                        Infolists\Components\TextEntry::make('date')
                            ->date('F j, Y'),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make('Body Measurements')
                    ->icon('heroicon-o-scale')
                    ->schema([
                        Infolists\Components\TextEntry::make('weight')
                            ->suffix(' kg')
                            ->badge()
                            ->color('info'),
                        Infolists\Components\TextEntry::make('waist')
                            ->suffix(' cm')
                            ->badge(),
                        Infolists\Components\TextEntry::make('chest')
                            ->suffix(' cm')
                            ->badge(),
                        Infolists\Components\TextEntry::make('hips')
                            ->suffix(' cm')
                            ->badge(),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make('Activity')
                    ->icon('heroicon-o-bolt')
                    ->schema([
                        Infolists\Components\TextEntry::make('workout_completed')
                            ->badge()
                            ->color('purple'),
                        Infolists\Components\TextEntry::make('mood')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'great' => 'success',
                                'good' => 'info',
                                'okay' => 'warning',
                                'tired' => 'gray',
                                'bad' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
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
