<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PlayerProfileResource\Pages;
use App\Models\PlayerProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PlayerProfileResource extends Resource
{
    protected static ?string $model = PlayerProfile::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 3;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.workout_logic');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.player_profiles');
    }

    public static function getModelLabel(): string
    {
        return __('filament.labels.profile_name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.player_profiles');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'purple';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.profile_identity'))
                    ->description(__('filament.sections.profile_identity_desc'))
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.labels.profile_name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.profile_name'))
                            ->helperText(__('filament.helper.profile_name')),
                        Forms\Components\Select::make('group')
                            ->label(__('filament.labels.profile_group'))
                            ->options([
                                'GARDIEN' => __('filament.profile_groups.gardien'),
                                'DÉFENSEUR' => __('filament.profile_groups.defenseur'),
                                'MILIEU' => __('filament.profile_groups.milieu'),
                                'ATTAQUANT' => __('filament.profile_groups.attaquant'),
                                'FITNESS_FEMME' => __('filament.profile_groups.fitness_femme'),
                                'FITNESS_HOMME' => __('filament.profile_groups.fitness_homme'),
                                'PADEL_DROITE' => __('filament.profile_groups.padel_droite'),
                                'PADEL_GAUCHE' => __('filament.profile_groups.padel_gauche'),
                                'PADEL_DEFENSE' => __('filament.profile_groups.padel_defense'),
                                'PADEL_PREVENTION' => __('filament.profile_groups.padel_prevention'),
                                'PADEL_SANTE' => __('filament.profile_groups.padel_sante'),
                                'PADEL_TIMING' => __('filament.profile_groups.padel_timing'),
                            ])
                            ->required()
                            ->native(false)
                            ->searchable(),
                        Forms\Components\Textarea::make('description')
                            ->label(__('filament.labels.description'))
                            ->rows(3)
                            ->placeholder(__('filament.placeholders.profile_desc'))
                            ->helperText(__('filament.helper.profile_desc'))
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.associated_themes'))
                    ->description(__('filament.sections.associated_themes_desc'))
                    ->icon('heroicon-o-link')
                    ->schema([
                        Forms\Components\Repeater::make('themes')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('id')
                                    ->label(__('filament.sections.theme'))
                                    ->relationship('themes', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\TextInput::make('pivot.percentage')
                                    ->label(__('filament.labels.percentage'))
                                    ->numeric()
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100),
                            ])
                            ->columns(2)
                            ->defaultItems(0)
                            ->addActionLabel(__('filament.actions.add_theme')),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.labels.profile_name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon(fn (PlayerProfile $record): string => self::getProfileIcon($record->name)),
                Tables\Columns\TextColumn::make('group')
                    ->label(__('filament.labels.group'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GARDIEN' => 'warning',
                        'DÉFENSEUR' => 'info',
                        'MILIEU' => 'success',
                        'ATTAQUANT' => 'danger',
                        'FITNESS_FEMME' => 'pink',
                        'FITNESS_HOMME' => 'purple',
                        'PADEL_DROITE', 'PADEL_GAUCHE', 'PADEL_DEFENSE', 'PADEL_PREVENTION', 'PADEL_SANTE', 'PADEL_TIMING' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label(__('filament.labels.description'))
                    ->limit(50)
                    ->tooltip(fn (PlayerProfile $record): ?string => $record->description)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('themes_count')
                    ->label(__('filament.labels.themes'))
                    ->counts('themes')
                    ->badge()
                    ->color('info'),
            ])
            ->defaultSort('group')
            ->defaultGroup('group')
            ->groups([
                Tables\Grouping\Group::make('group')
                    ->label(__('filament.labels.profile_group'))
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->options([
                        'GARDIEN' => __('filament.profile_groups.gardien'),
                        'DÉFENSEUR' => __('filament.profile_groups.defenseur'),
                        'MILIEU' => __('filament.profile_groups.milieu'),
                        'ATTAQUANT' => __('filament.profile_groups.attaquant'),
                        'FITNESS_FEMME' => __('filament.profile_groups.fitness_femme'),
                        'FITNESS_HOMME' => __('filament.profile_groups.fitness_homme'),
                        'PADEL_DROITE' => __('filament.profile_groups.padel_droite'),
                        'PADEL_GAUCHE' => __('filament.profile_groups.padel_gauche'),
                        'PADEL_DEFENSE' => __('filament.profile_groups.padel_defense'),
                        'PADEL_PREVENTION' => __('filament.profile_groups.padel_prevention'),
                        'PADEL_SANTE' => __('filament.profile_groups.padel_sante'),
                        'PADEL_TIMING' => __('filament.profile_groups.padel_timing'),
                    ])
                    ->multiple(),
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
            ->emptyStateHeading(__('filament.empty.player_profiles'))
            ->emptyStateDescription(__('filament.empty.player_profiles_desc'))
            ->emptyStateIcon('heroicon-o-user-group');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.profile_details'))
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('filament.labels.name'))
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('group')
                            ->label(__('filament.labels.group'))
                            ->badge(),
                        Infolists\Components\TextEntry::make('description')
                            ->label(__('filament.labels.description'))
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('filament.sections.associated_themes'))
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('themes')
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->badge(),
                                Infolists\Components\TextEntry::make('pivot.percentage')
                                    ->label(__('filament.labels.weight'))
                                    ->suffix('%'),
                            ])
                            ->columns(2),
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
            'index' => Pages\ListPlayerProfiles::route('/'),
            'create' => Pages\CreatePlayerProfile::route('/create'),
            'edit' => Pages\EditPlayerProfile::route('/{record}/edit'),
        ];
    }

    private static function getProfileIcon(string $name): string
    {
        return match (strtolower($name)) {
            'tank', 'sentinelle', 'muraille' => 'heroicon-o-shield-check',
            'magicien', 'maestro', 'architecte' => 'heroicon-o-sparkles',
            'faucon', 'renard', 'flèche' => 'heroicon-o-bolt',
            'guerrier', 'gladiateur' => 'heroicon-o-fire',
            default => 'heroicon-o-user',
        };
    }
}
