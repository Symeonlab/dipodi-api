<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UserGoalResource\Pages;
use App\Models\UserGoal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class UserGoalResource extends Resource
{
    protected static ?string $model = UserGoal::class;
    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.user_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.user_goals');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.user_goals');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.user_goals');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.goal_details'))
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('goal_type')
                            ->options([
                                'weight_loss' => __('filament.goals.weight_loss'),
                                'muscle_gain' => __('filament.goals.muscle_gain'),
                                'maintain' => __('filament.goals.maintain'),
                                'custom' => __('filament.goals.custom'),
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'active' => __('filament.goal_statuses.active'),
                                'completed' => __('filament.goal_statuses.completed'),
                                'paused' => __('filament.goal_statuses.paused'),
                                'abandoned' => __('filament.goal_statuses.abandoned'),
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('filament.sections.target_metrics'))
                    ->schema([
                        Forms\Components\TextInput::make('target_weight')
                            ->numeric()
                            ->suffix('kg'),
                        Forms\Components\TextInput::make('target_waist')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('target_chest')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('target_hips')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('target_workouts_per_week')
                            ->numeric()
                            ->default(3)
                            ->minValue(1)
                            ->maxValue(7),
                    ])
                    ->columns(5),

                Forms\Components\Section::make(__('filament.sections.starting_metrics'))
                    ->schema([
                        Forms\Components\TextInput::make('start_weight')
                            ->numeric()
                            ->suffix('kg'),
                        Forms\Components\TextInput::make('start_waist')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('start_chest')
                            ->numeric()
                            ->suffix('cm'),
                        Forms\Components\TextInput::make('start_hips')
                            ->numeric()
                            ->suffix('cm'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make(__('filament.sections.timeline'))
                    ->schema([
                        Forms\Components\DatePicker::make('start_date')
                            ->required()
                            ->default(now()),
                        Forms\Components\DatePicker::make('target_date')
                            ->required()
                            ->default(now()->addWeeks(12)),
                        Forms\Components\TextInput::make('total_weeks')
                            ->numeric()
                            ->default(12),
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('filament.sections.progress'))
                    ->schema([
                        Forms\Components\TextInput::make('current_progress_percentage')
                            ->numeric()
                            ->suffix('%')
                            ->default(0),
                        Forms\Components\TextInput::make('weeks_completed')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                Forms\Components\Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('filament.labels.user'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('goal_type')
                    ->label(__('filament.labels.goal_type'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'weight_loss' => __('filament.goals.weight_loss'),
                        'muscle_gain' => __('filament.goals.muscle_gain'),
                        'maintain' => __('filament.goals.maintain'),
                        'custom' => __('filament.goals.custom'),
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'weight_loss' => 'danger',
                        'muscle_gain' => 'success',
                        'maintain' => 'primary',
                        'custom' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('current_progress_percentage')
                    ->label(__('filament.labels.progress'))
                    ->suffix('%')
                    ->color(fn (UserGoal $record): string =>
                        $record->current_progress_percentage >= 75 ? 'success' :
                        ($record->current_progress_percentage >= 50 ? 'warning' : 'danger')
                    )
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'primary',
                        'completed' => 'success',
                        'paused' => 'warning',
                        'abandoned' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('target_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('weeks_completed')
                    ->label(__('filament.labels.weeks'))
                    ->formatStateUsing(fn (UserGoal $record): string =>
                        "{$record->weeks_completed}/{$record->total_weeks}"
                    ),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => __('filament.goal_statuses.active'),
                        'completed' => __('filament.goal_statuses.completed'),
                        'paused' => __('filament.goal_statuses.paused'),
                        'abandoned' => __('filament.goal_statuses.abandoned'),
                    ]),
                Tables\Filters\SelectFilter::make('goal_type')
                    ->options([
                        'weight_loss' => __('filament.goals.weight_loss'),
                        'muscle_gain' => __('filament.goals.muscle_gain'),
                        'maintain' => __('filament.goals.maintain'),
                        'custom' => __('filament.goals.custom'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.goal_overview'))
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label(__('filament.labels.user')),
                        Infolists\Components\TextEntry::make('goal_type')
                            ->label(__('filament.labels.goal_type'))
                            ->badge(),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'active' => 'primary',
                                'completed' => 'success',
                                'paused' => 'warning',
                                'abandoned' => 'danger',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('current_progress_percentage')
                            ->label(__('filament.labels.progress'))
                            ->suffix('%'),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make(__('filament.sections.metrics_comparison'))
                    ->schema([
                        Infolists\Components\Grid::make(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('start_weight')
                                    ->label(__('filament.labels.start_weight'))
                                    ->suffix(' kg'),
                                Infolists\Components\TextEntry::make('target_weight')
                                    ->label(__('filament.labels.target_weight'))
                                    ->suffix(' kg'),
                                Infolists\Components\TextEntry::make('start_waist')
                                    ->label(__('filament.labels.start_waist'))
                                    ->suffix(' cm'),
                                Infolists\Components\TextEntry::make('target_waist')
                                    ->label(__('filament.labels.target_waist'))
                                    ->suffix(' cm'),
                            ]),
                    ]),

                Infolists\Components\Section::make(__('filament.sections.timeline'))
                    ->schema([
                        Infolists\Components\TextEntry::make('start_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('target_date')
                            ->date(),
                        Infolists\Components\TextEntry::make('weeks_completed')
                            ->label(__('filament.labels.weeks_completed'))
                            ->formatStateUsing(fn (UserGoal $record): string =>
                                "{$record->weeks_completed} / {$record->total_weeks}"
                            ),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('filament.resources.achievements'))
                    ->schema([
                        Infolists\Components\TextEntry::make('achievements')
                            ->label(__('filament.labels.unlocked_achievements'))
                            ->badge()
                            ->separator(','),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserGoals::route('/'),
            'create' => Pages\CreateUserGoal::route('/create'),
            'view' => Pages\ViewUserGoal::route('/{record}'),
            'edit' => Pages\EditUserGoal::route('/{record}/edit'),
        ];
    }
}
