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
            'Foods to Avoid' => "{$avoidCount} items",
            'Foods to Eat' => "{$eatCount} items",
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
                            ->label('Condition Name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Fatigue, Crampes, Migraine')
                            ->columnSpan(2),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.food_recommendations'))
                    ->description(__('filament.sections.food_recommendations_desc'))
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\TagsInput::make('foods_to_avoid')
                            ->label('Foods to Avoid')
                            ->placeholder('Add foods to avoid...')
                            ->helperText('Press Enter after each food item')
                            ->color('danger'),
                        Forms\Components\TagsInput::make('foods_to_eat')
                            ->label('Foods to Eat')
                            ->placeholder('Add recommended foods...')
                            ->helperText('Press Enter after each food item')
                            ->color('success'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.prophetic_medicine'))
                    ->description(__('filament.sections.prophetic_medicine_desc'))
                    ->icon('heroicon-o-book-open')
                    ->collapsible()
                    ->schema([
                        Forms\Components\Textarea::make('prophetic_advice_fr')
                            ->label('Prophetic Advice (Francais)')
                            ->rows(3)
                            ->placeholder('Conseil en francais...')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('prophetic_advice_en')
                            ->label('Prophetic Advice (English)')
                            ->rows(3)
                            ->placeholder('Advice in English...')
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('prophetic_advice_ar')
                            ->label('Prophetic Advice (Arabic)')
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
                    ->label('Condition')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->icon('heroicon-o-heart'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
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
                    ->label('Foods to Avoid')
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
                    ->label('Foods to Eat')
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
                    ->label('Prophetic')
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
                    ->label('Has Prophetic Advice')
                    ->query(fn (Builder $query): Builder => $query->where(function ($q) {
                        $q->whereNotNull('prophetic_advice_fr')
                          ->orWhereNotNull('prophetic_advice_en')
                          ->orWhereNotNull('prophetic_advice_ar');
                    })),
                Tables\Filters\Filter::make('sport_conditions')
                    ->label('Sport Conditions')
                    ->query(fn (Builder $query): Builder => $query->whereIn('condition_name', [
                        'Fatigue', 'Crampes', 'Blessures', 'Tendinites', 'Entorse', 'Inflammation',
                        'Perte de poids', 'Prise de masse', 'Seche', 'Performance', 'Recuperation'
                    ])),
                Tables\Filters\Filter::make('prophetic_medicine')
                    ->label('Prophetic Medicine')
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
                        ->label('Export Selected')
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
            ->emptyStateHeading('No nutrition advice yet')
            ->emptyStateDescription('Create your first nutrition advice entry to help users manage health conditions.')
            ->emptyStateIcon('heroicon-o-light-bulb');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Condition')
                    ->schema([
                        Infolists\Components\TextEntry::make('condition_name')
                            ->label('Condition Name')
                            ->size('lg')
                            ->weight('bold'),
                    ]),
                Infolists\Components\Section::make('Food Recommendations')
                    ->schema([
                        Infolists\Components\TextEntry::make('foods_to_avoid')
                            ->label('Foods to Avoid')
                            ->badge()
                            ->color('danger')
                            ->separator(', '),
                        Infolists\Components\TextEntry::make('foods_to_eat')
                            ->label('Foods to Eat')
                            ->badge()
                            ->color('success')
                            ->separator(', '),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Prophetic Medicine Advice')
                    ->schema([
                        Infolists\Components\TextEntry::make('prophetic_advice_fr')
                            ->label('Francais')
                            ->markdown(),
                        Infolists\Components\TextEntry::make('prophetic_advice_en')
                            ->label('English')
                            ->markdown(),
                        Infolists\Components\TextEntry::make('prophetic_advice_ar')
                            ->label('Arabic')
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
