<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\AchievementResource\Pages;
use App\Models\Achievement;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AchievementResource extends Resource
{
    protected static ?string $model = Achievement::class;
    protected static ?string $navigationIcon = 'heroicon-o-trophy';
    protected static ?int $navigationSort = 6;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.achievements');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.achievements');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.achievements');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.achievement_details'))
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100),

                        Forms\Components\Select::make('category')
                            ->options([
                                'workout' => __('filament.labels.workout_type'),
                                'consistency' => __('filament.labels.consistency'),
                                'milestone' => __('filament.labels.milestone'),
                                'nutrition' => __('filament.labels.nutrition'),
                                'special' => __('filament.labels.special'),
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('points')
                            ->numeric()
                            ->required()
                            ->default(10),

                        Forms\Components\TextInput::make('icon')
                            ->label(__('filament.labels.icon'))
                            ->placeholder('trophy.fill'),
                    ])
                    ->columns(4),

                Forms\Components\Section::make(__('filament.labels.english'))
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('filament.labels.name'))
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Textarea::make('description_en')
                            ->label(__('filament.labels.description'))
                            ->required()
                            ->rows(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.labels.french'))
                    ->schema([
                        Forms\Components\TextInput::make('name_fr')
                            ->label(__('filament.labels.name'))
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Textarea::make('description_fr')
                            ->label(__('filament.labels.description'))
                            ->required()
                            ->rows(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.labels.arabic'))
                    ->schema([
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('filament.labels.name'))
                            ->required()
                            ->maxLength(100),
                        Forms\Components\Textarea::make('description_ar')
                            ->label(__('filament.labels.description'))
                            ->required()
                            ->rows(2),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('')
                    ->formatStateUsing(fn ($state) => $state ? "📱 {$state}" : '🏆'),

                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('filament.labels.name'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'workout' => 'primary',
                        'consistency' => 'success',
                        'milestone' => 'warning',
                        'nutrition' => 'info',
                        'special' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('points')
                    ->badge()
                    ->color('success')
                    ->sortable(),

                Tables\Columns\TextColumn::make('users_count')
                    ->counts('users')
                    ->label(__('filament.widgets.earned_by'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'workout' => __('filament.labels.workout_type'),
                        'consistency' => __('filament.labels.consistency'),
                        'milestone' => __('filament.labels.milestone'),
                        'nutrition' => __('filament.labels.nutrition'),
                        'special' => __('filament.labels.special'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('points', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAchievements::route('/'),
            'create' => Pages\CreateAchievement::route('/create'),
            'edit' => Pages\EditAchievement::route('/{record}/edit'),
        ];
    }
}
