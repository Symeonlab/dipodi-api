<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FoodItemResource\Pages;
use App\Models\FoodItem;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class FoodItemResource extends Resource
{
    protected static ?string $model = FoodItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-cake';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.nutrition_health');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.food_items');
    }

    public static function getModelLabel(): string
    {
        return __('filament.labels.food_name');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.food_items');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'category'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Category' => match ($record->category) {
                'petitDejeuner' => 'Petit Dejeuner',
                'platPrincipal' => 'Plat Principal',
                'accompagnement' => 'Accompagnement',
                'dessert' => 'Dessert',
                default => $record->category,
            },
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.food_info'))
                    ->description(__('filament.sections.food_info_desc'))
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label(__('filament.labels.food_name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.food_name')),
                        Forms\Components\Select::make('category')
                            ->label(__('filament.labels.meal_category'))
                            ->options([
                                'petitDejeuner' => __('filament.food_categories.petit_dejeuner'),
                                'platPrincipal' => __('filament.food_categories.plat_principal'),
                                'accompagnement' => __('filament.food_categories.accompagnement'),
                                'dessert' => __('filament.food_categories.dessert'),
                            ])
                            ->required()
                            ->native(false)
                            ->searchable(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.sport_nutrition'))
                    ->description(__('filament.sections.sport_nutrition_desc'))
                    ->icon('heroicon-o-bolt')
                    ->schema([
                        Forms\Components\TextInput::make('h_plus_1_energy')
                            ->label(__('filament.labels.h1_energy'))
                            ->numeric()
                            ->step(0.1)
                            ->helperText(__('filament.helper.h1_energy')),
                        Forms\Components\TextInput::make('h_plus_24_recovery')
                            ->label(__('filament.labels.h24_recovery'))
                            ->numeric()
                            ->step(0.1)
                            ->helperText(__('filament.helper.h24_recovery')),
                        Forms\Components\Select::make('meal_timing')
                            ->label(__('filament.labels.meal_timing'))
                            ->options([
                                'pre_workout' => __('filament.meal_timing.pre_workout'),
                                'post_workout' => __('filament.meal_timing.post_workout'),
                                'recovery' => __('filament.meal_timing.recovery'),
                                'any' => __('filament.meal_timing.any'),
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->columns(3)
                    ->collapsible(),

                Forms\Components\Section::make(__('filament.sections.food_properties'))
                    ->description(__('filament.sections.food_properties_desc'))
                    ->icon('heroicon-o-tag')
                    ->schema([
                        Forms\Components\TagsInput::make('tags')
                            ->label(__('filament.labels.tags'))
                            ->placeholder(__('filament.placeholders.add_tags'))
                            ->helperText(__('filament.helper.tags_food'))
                            ->suggestions([
                                'fruit', 'legume', 'viande', 'poisson', 'volaille',
                                'produitLaitier', 'cereale', 'feculent', 'noix',
                                'vegetarien', 'vegan', 'halal', 'bio',
                                'printemps', 'ete', 'automne', 'hiver',
                                'riche_proteines', 'riche_fibres', 'faible_calories',
                                'sans_gluten', 'sans_lactose'
                            ])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.labels.food_name'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-cake'),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('filament.labels.category'))
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'petitDejeuner' => 'Petit Dejeuner',
                        'platPrincipal' => 'Plat Principal',
                        'accompagnement' => 'Accompagnement',
                        'dessert' => 'Dessert',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'petitDejeuner' => 'warning',
                        'platPrincipal' => 'success',
                        'accompagnement' => 'info',
                        'dessert' => 'danger',
                        default => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'petitDejeuner' => 'heroicon-o-sun',
                        'platPrincipal' => 'heroicon-o-fire',
                        'accompagnement' => 'heroicon-o-squares-plus',
                        'dessert' => 'heroicon-o-cake',
                        default => 'heroicon-o-circle-stack',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('food_type')
                    ->label(__('filament.labels.type'))
                    ->badge()
                    ->getStateUsing(function (FoodItem $record): string {
                        $tags = $record->tags ?? [];
                        if (in_array('fruit', $tags)) return 'Fruit';
                        if (in_array('legume', $tags)) return 'Legume';
                        if (in_array('viande', $tags) || in_array('volaille', $tags)) return 'Viande';
                        if (in_array('poisson', $tags)) return 'Poisson';
                        if (in_array('produitLaitier', $tags)) return 'Laitier';
                        if (in_array('cereale', $tags) || in_array('feculent', $tags)) return 'Feculent';
                        if (in_array('noix', $tags)) return 'Noix';
                        return 'Autre';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Fruit' => 'success',
                        'Legume' => 'success',
                        'Viande' => 'danger',
                        'Poisson' => 'info',
                        'Laitier' => 'warning',
                        'Feculent' => 'gray',
                        'Noix' => 'purple',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('season')
                    ->label(__('filament.filters.season'))
                    ->badge()
                    ->getStateUsing(function (FoodItem $record): ?string {
                        $tags = $record->tags ?? [];
                        $seasons = [];
                        if (in_array('printemps', $tags)) $seasons[] = 'Printemps';
                        if (in_array('ete', $tags)) $seasons[] = 'Ete';
                        if (in_array('automne', $tags)) $seasons[] = 'Automne';
                        if (in_array('hiver', $tags)) $seasons[] = 'Hiver';
                        return empty($seasons) ? 'Toute annee' : implode(', ', $seasons);
                    })
                    ->color('gray'),
                Tables\Columns\TextColumn::make('tags')
                    ->label(__('filament.labels.tags'))
                    ->badge()
                    ->color('gray')
                    ->separator(', ')
                    ->limit(4)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('filament.labels.category'))
                    ->options([
                        'petitDejeuner' => __('filament.food_categories.petit_dejeuner'),
                        'platPrincipal' => __('filament.food_categories.plat_principal'),
                        'accompagnement' => __('filament.food_categories.accompagnement'),
                        'dessert' => __('filament.food_categories.dessert'),
                    ])
                    ->multiple(),
                Tables\Filters\SelectFilter::make('food_type')
                    ->label(__('filament.filters.food_type'))
                    ->options([
                        'fruit' => __('filament.food_types.fruit'),
                        'legume' => __('filament.food_types.vegetable'),
                        'viande' => __('filament.food_types.meat'),
                        'volaille' => __('filament.food_types.poultry'),
                        'poisson' => __('filament.food_types.fish'),
                        'produitLaitier' => __('filament.food_types.dairy'),
                        'cereale' => __('filament.food_types.grain'),
                        'feculent' => __('filament.food_types.starch'),
                        'noix' => __('filament.food_types.nuts'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }
                        return $query->whereJsonContains('tags', $data['value']);
                    }),
                Tables\Filters\SelectFilter::make('season')
                    ->label(__('filament.filters.season'))
                    ->options([
                        'printemps' => __('filament.seasons.spring'),
                        'ete' => __('filament.seasons.summer'),
                        'automne' => __('filament.seasons.autumn'),
                        'hiver' => __('filament.seasons.winter'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }
                        return $query->whereJsonContains('tags', $data['value']);
                    }),
                Tables\Filters\Filter::make('vegetarien')
                    ->label(__('filament.filters.vegetarian'))
                    ->query(fn (Builder $query): Builder => $query->whereJsonContains('tags', 'vegetarien')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateCategory')
                        ->label(__('filament.actions.update_category'))
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('category')
                                ->label(__('filament.labels.new_category'))
                                ->options([
                                    'petitDejeuner' => __('filament.food_categories.petit_dejeuner'),
                                    'platPrincipal' => __('filament.food_categories.plat_principal'),
                                    'accompagnement' => __('filament.food_categories.accompagnement'),
                                    'dessert' => __('filament.food_categories.dessert'),
                                ])
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            $records->each(fn ($record) => $record->update(['category' => $data['category']]));
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('addTag')
                        ->label(__('filament.actions.add_tag'))
                        ->icon('heroicon-o-plus')
                        ->form([
                            Forms\Components\TextInput::make('tag')
                                ->label(__('filament.labels.tag_to_add'))
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            $records->each(function ($record) use ($data) {
                                $tags = $record->tags ?? [];
                                if (!in_array($data['tag'], $tags)) {
                                    $tags[] = $data['tag'];
                                    $record->update(['tags' => $tags]);
                                }
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')
                        ->label(__('filament.actions.export_selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csv = "Name,Category,Tags\n";
                            foreach ($records as $record) {
                                $tags = is_array($record->tags) ? implode(';', $record->tags) : '';
                                $csv .= "\"{$record->name}\",\"{$record->category}\",\"{$tags}\"\n";
                            }
                            return response()->streamDownload(fn () => print($csv), 'food-items-export.csv');
                        }),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.food_items'))
            ->emptyStateDescription(__('filament.empty.food_items_desc'))
            ->emptyStateIcon('heroicon-o-cake');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.food_details'))
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('filament.labels.name'))
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('category')
                            ->label(__('filament.labels.category'))
                            ->badge()
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'petitDejeuner' => 'Petit Dejeuner',
                                'platPrincipal' => 'Plat Principal',
                                'accompagnement' => 'Accompagnement',
                                'dessert' => 'Dessert',
                                default => $state,
                            }),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('filament.sections.tags'))
                    ->schema([
                        Infolists\Components\TextEntry::make('tags')
                            ->label(__('filament.labels.tags'))
                            ->badge()
                            ->color('info')
                            ->separator(', '),
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
            'index' => Pages\ListFoodItems::route('/'),
            'create' => Pages\CreateFoodItem::route('/create'),
            'edit' => Pages\EditFoodItem::route('/{record}/edit'),
        ];
    }
}
