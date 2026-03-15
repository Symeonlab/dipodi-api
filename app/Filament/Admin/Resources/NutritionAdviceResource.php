<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\NutritionAdviceResource\Pages;
use App\Models\NutritionAdvice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class NutritionAdviceResource extends Resource
{
    protected static ?string $model = NutritionAdvice::class;

    protected static ?string $navigationIcon = 'heroicon-o-light-bulb';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'condition_name';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.nutrition_health');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.nutrition_advice');
    }

    public static function getModelLabel(): string
    {
        return __('filament.labels.condition');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.nutrition_advice');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['condition_name'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        $avoid = $record->foods_to_avoid;
        $eat = $record->foods_to_eat;
        $avoidCount = is_array($avoid) ? count($avoid) : (empty($avoid) ? 0 : 1);
        $eatCount = is_array($eat) ? count($eat) : (empty($eat) ? 0 : 1);
        return [
            __('filament.labels.foods_to_avoid') => "{$avoidCount} items",
            __('filament.labels.foods_to_eat') => "{$eatCount} items",
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.condition_info'))
                    ->description(__('filament.sections.condition_info_desc'))
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('condition_name')
                            ->label(__('filament.labels.condition_name'))
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.condition'))
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.food_recommendations'))
                    ->description(__('filament.sections.food_recommendations_desc'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\TagsInput::make('foods_to_avoid')
                            ->label(__('filament.labels.foods_to_avoid'))
                            ->placeholder(__('filament.placeholders.add_foods_avoid'))
                            ->helperText(__('filament.helper.press_enter_each'))
                            ->color('danger'),
                        Forms\Components\TagsInput::make('foods_to_eat')
                            ->label(__('filament.labels.foods_to_eat'))
                            ->placeholder(__('filament.placeholders.add_foods_eat'))
                            ->helperText(__('filament.helper.press_enter_each'))
                            ->color('success'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.prophetic_medicine'))
                    ->description(__('filament.sections.prophetic_medicine_desc'))
                    ->icon('heroicon-o-book-open')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Textarea::make('prophetic_advice_fr')
                            ->label(__('filament.labels.prophetic_advice_fr'))
                            ->rows(3)
                            ->placeholder(__('filament.placeholders.advice_fr'))
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('prophetic_advice_en')
                            ->label(__('filament.labels.prophetic_advice_en'))
                            ->rows(3)
                            ->placeholder(__('filament.placeholders.advice_en'))
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('prophetic_advice_ar')
                            ->label(__('filament.labels.prophetic_advice_ar'))
                            ->rows(3)
                            ->placeholder('...')
                            ->extraAttributes(['dir' => 'rtl'])
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('condition_name')
                    ->label(__('filament.labels.condition'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-heart'),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('filament.labels.category'))
                    ->badge()
                    ->getStateUsing(function (NutritionAdvice $record): string {
                        $name = strtolower($record->condition_name);
                        if (str_contains($name, 'football') || in_array($name, ['fatigue', 'crampes', 'blessures', 'tendinites', 'entorse', 'inflammation'])) {
                            return 'Football';
                        }
                        if (str_contains($name, 'fitness') || in_array($name, ['perte de poids', 'prise de masse', 'seche'])) {
                            return 'Fitness';
                        }
                        if (str_contains($name, 'padel')) {
                            return 'Padel';
                        }
                        // Prophetic medicine conditions
                        $propheticConditions = ['toux', 'pharyngite', 'migraine', 'depression', 'insomnies', 'angine', 'anemie', 'diabete', 'cholesterol', 'hypertension', 'asthme', 'constipation', 'diarrhee', 'nausees', 'brulures', 'grippe', 'rhume'];
                        foreach ($propheticConditions as $condition) {
                            if (str_contains($name, $condition)) {
                                return 'Prophetic Medicine';
                            }
                        }
                        return 'Sport';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Football' => 'success',
                        'Fitness' => 'info',
                        'Padel' => 'warning',
                        'Prophetic Medicine' => 'purple',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('foods_to_avoid')
                    ->label(__('filament.labels.foods_to_avoid'))
                    ->badge()
                    ->color('danger')
                    ->separator(', ')
                    ->limit(3)
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }
                        return $state ?? '';
                    })
                    ->tooltip(function (NutritionAdvice $record): string {
                        $value = $record->foods_to_avoid;
                        if (is_array($value)) {
                            return implode(', ', $value);
                        }
                        return $value ?? '';
                    }),
                Tables\Columns\TextColumn::make('foods_to_eat')
                    ->label(__('filament.labels.foods_to_eat'))
                    ->badge()
                    ->color('success')
                    ->separator(', ')
                    ->limit(3)
                    ->formatStateUsing(function ($state) {
                        if (is_array($state)) {
                            return implode(', ', $state);
                        }
                        return $state ?? '';
                    })
                    ->tooltip(function (NutritionAdvice $record): string {
                        $value = $record->foods_to_eat;
                        if (is_array($value)) {
                            return implode(', ', $value);
                        }
                        return $value ?? '';
                    }),
                Tables\Columns\IconColumn::make('has_prophetic_advice')
                    ->label(__('filament.labels.prophetic_advice'))
                    ->boolean()
                    ->getStateUsing(fn (NutritionAdvice $record): bool => !empty($record->prophetic_advice_fr) || !empty($record->prophetic_advice_en)),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('condition_name')
            ->filters([
                Tables\Filters\Filter::make('has_prophetic_advice')
                    ->label(__('filament.filters.has_prophetic_advice'))
                    ->query(fn (Builder $query): Builder => $query->where(function ($q) {
                        $q->whereNotNull('prophetic_advice_fr')
                          ->orWhereNotNull('prophetic_advice_en')
                          ->orWhereNotNull('prophetic_advice_ar');
                    })),
                Tables\Filters\Filter::make('sport_conditions')
                    ->label(__('filament.filters.sport_conditions'))
                    ->query(fn (Builder $query): Builder => $query->whereIn('condition_name', [
                        'Fatigue', 'Crampes', 'Blessures', 'Tendinites', 'Entorse', 'Inflammation',
                        'Perte de poids', 'Prise de masse', 'Seche', 'Performance', 'Recuperation'
                    ])),
                Tables\Filters\Filter::make('prophetic_medicine')
                    ->label(__('filament.filters.prophetic_medicine'))
                    ->query(fn (Builder $query): Builder => $query->whereIn('condition_name', [
                        'Toux', 'Pharyngite', 'Migraine', 'Depression', 'Insomnies', 'Angine',
                        'Anemie', 'Diabete', 'Cholesterol', 'Hypertension', 'Asthme'
                    ])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label(__('filament.actions.export_selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csv = "Condition,Foods to Avoid,Foods to Eat,Prophetic Advice (FR)\n";
                            foreach ($records as $record) {
                                $avoid = is_array($record->foods_to_avoid) ? implode(';', $record->foods_to_avoid) : '';
                                $eat = is_array($record->foods_to_eat) ? implode(';', $record->foods_to_eat) : '';
                                $advice = str_replace(["\n", "\r", '"'], [' ', ' ', '""'], $record->prophetic_advice_fr ?? '');
                                $csv .= "\"{$record->condition_name}\",\"{$avoid}\",\"{$eat}\",\"{$advice}\"\n";
                            }
                            return response()->streamDownload(fn () => print($csv), 'nutrition-advice-export.csv');
                        }),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.nutrition_advice'))
            ->emptyStateDescription(__('filament.empty.nutrition_advice_desc'))
            ->emptyStateIcon('heroicon-o-light-bulb');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.labels.condition'))
                    ->schema([
                        Infolists\Components\TextEntry::make('condition_name')
                            ->label(__('filament.labels.condition_name'))
                            ->size('lg')
                            ->weight('bold'),
                    ]),
                Infolists\Components\Section::make(__('filament.sections.food_recommendations'))
                    ->schema([
                        Infolists\Components\TextEntry::make('foods_to_avoid')
                            ->label(__('filament.labels.foods_to_avoid'))
                            ->badge()
                            ->color('danger')
                            ->separator(', '),
                        Infolists\Components\TextEntry::make('foods_to_eat')
                            ->label(__('filament.labels.foods_to_eat'))
                            ->badge()
                            ->color('success')
                            ->separator(', '),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('filament.sections.prophetic_medicine'))
                    ->schema([
                        Infolists\Components\TextEntry::make('prophetic_advice_fr')
                            ->label(__('filament.labels.french'))
                            ->markdown(),
                        Infolists\Components\TextEntry::make('prophetic_advice_en')
                            ->label(__('filament.labels.english'))
                            ->markdown(),
                        Infolists\Components\TextEntry::make('prophetic_advice_ar')
                            ->label(__('filament.labels.arabic'))
                            ->markdown()
                            ->extraAttributes(['dir' => 'rtl']),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNutritionAdvice::route('/'),
            'create' => Pages\CreateNutritionAdvice::route('/create'),
            'edit' => Pages\EditNutritionAdvice::route('/{record}/edit'),
            'view' => Pages\ViewNutritionAdvice::route('/{record}'),
        ];
    }
}
