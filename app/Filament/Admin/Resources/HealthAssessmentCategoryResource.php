<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HealthAssessmentCategoryResource\Pages;
use App\Filament\Admin\Resources\HealthAssessmentCategoryResource\RelationManagers;
use App\Models\HealthAssessmentCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HealthAssessmentCategoryResource extends Resource
{
    protected static ?string $model = HealthAssessmentCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?int $navigationSort = 30;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.health_assessment');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.health_categories');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.health_categories');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.category_details'))
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label(__('filament.labels.key') . ' (unique identifier)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->helperText(__('filament.helper.key_format')),
                        Forms\Components\TextInput::make('name_fr')
                            ->label(__('filament.labels.french_name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('filament.labels.english_name'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('filament.labels.arabic_name'))
                            ->maxLength(255),
                        Forms\Components\TextInput::make('icon')
                            ->label(__('filament.labels.icon'))
                            ->helperText(__('filament.helper.sf_symbol'))
                            ->maxLength(50),
                        Forms\Components\Select::make('discipline')
                            ->label(__('filament.labels.discipline'))
                            ->options([
                                'football' => __('filament.disciplines.football'),
                                'fitness' => __('filament.disciplines.fitness'),
                                null => __('filament.placeholders.all_disciplines'),
                            ])
                            ->placeholder(__('filament.placeholders.all_disciplines')),
                        Forms\Components\TextInput::make('sort_order')
                            ->label(__('filament.labels.sort_order'))
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament.labels.active'))
                            ->default(true),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),
                Tables\Columns\TextColumn::make('key')
                    ->label(__('filament.labels.key'))
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('filament.labels.name_en'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_fr')
                    ->label(__('filament.labels.name_fr'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('icon')
                    ->label(__('filament.labels.icon')),
                Tables\Columns\TextColumn::make('discipline')
                    ->label(__('filament.labels.discipline'))
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'football' => 'info',
                        'fitness' => 'success',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label(__('filament.labels.questions'))
                    ->counts('questions')
                    ->badge()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.labels.active'))
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('discipline')
                    ->options([
                        'football' => __('filament.disciplines.football'),
                        'fitness' => __('filament.disciplines.fitness'),
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament.labels.active')),
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
            ->reorderable('sort_order');
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHealthAssessmentCategories::route('/'),
            'create' => Pages\CreateHealthAssessmentCategory::route('/create'),
            'view' => Pages\ViewHealthAssessmentCategory::route('/{record}'),
            'edit' => Pages\EditHealthAssessmentCategory::route('/{record}/edit'),
        ];
    }
}
