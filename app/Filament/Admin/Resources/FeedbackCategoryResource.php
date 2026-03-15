<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\FeedbackCategoryResource\Pages;
use App\Filament\Admin\Resources\FeedbackCategoryResource\RelationManagers;
use App\Models\FeedbackCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FeedbackCategoryResource extends Resource
{
    protected static ?string $model = FeedbackCategory::class;
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 26;
    protected static ?string $recordTitleAttribute = 'name_en';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.feedback');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.categories_questions');
    }

    public static function getModelLabel(): string
    {
        return __('filament.resources.feedback_category');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.feedback_categories');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_active', true)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.category_details'))
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(100)
                            ->helperText(__('filament.helper.key_format'))
                            ->regex('/^[a-z0-9_]+$/')
                            ->validationMessages([
                                'regex' => 'Key must contain only lowercase letters, numbers, and underscores.',
                            ]),
                        Forms\Components\TextInput::make('icon')
                            ->maxLength(50)
                            ->helperText(__('filament.helper.sf_symbol')),
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('filament.labels.active'))
                            ->default(true),
                        Forms\Components\TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.names_localized'))
                    ->schema([
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
                    ])
                    ->columns(3),

                Forms\Components\Section::make(__('filament.sections.targeting'))
                    ->description(__('filament.sections.targeting_desc'))
                    ->schema([
                        Forms\Components\Select::make('discipline')
                            ->options([
                                'football' => __('filament.disciplines.football'),
                                'fitness' => __('filament.disciplines.fitness'),
                            ])
                            ->placeholder(__('filament.placeholders.all_disciplines')),
                        Forms\Components\TextInput::make('position')
                            ->placeholder(__('filament.placeholders.positions'))
                            ->helperText(__('filament.helper.for_football')),
                        Forms\Components\Select::make('goal')
                            ->options([
                                'weight_loss' => __('filament.goals.weight_loss'),
                                'muscle_gain' => __('filament.goals.muscle_gain'),
                                'maintain' => __('filament.goals.maintain'),
                                'performance' => __('filament.goals.performance'),
                            ])
                            ->placeholder(__('filament.placeholders.all_goals')),
                        Forms\Components\Toggle::make('requires_injury')
                            ->label(__('filament.labels.requires_injury'))
                            ->helperText(__('filament.helper.only_injury')),
                    ])
                    ->columns(2)
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->size('sm'),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('filament.labels.name_en'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('name_fr')
                    ->label(__('filament.labels.name_fr'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('icon')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('filament.labels.active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('discipline')
                    ->badge()
                    ->color('info')
                    ->placeholder(__('filament.placeholders.all')),
                Tables\Columns\TextColumn::make('position')
                    ->badge()
                    ->color('purple')
                    ->placeholder('-'),
                Tables\Columns\TextColumn::make('questions_count')
                    ->label(__('filament.labels.questions'))
                    ->counts('questions')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('sessions_count')
                    ->label(__('filament.labels.sessions'))
                    ->counts('sessions')
                    ->badge()
                    ->color('warning'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->label(__('filament.labels.order'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('filament.labels.active_status')),
                Tables\Filters\SelectFilter::make('discipline')
                    ->options([
                        'football' => __('filament.disciplines.football'),
                        'fitness' => __('filament.disciplines.fitness'),
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
            ->reorderable('sort_order')
            ->emptyStateHeading(__('filament.empty.feedback_categories'))
            ->emptyStateDescription(__('filament.empty.feedback_categories_desc'))
            ->emptyStateIcon('heroicon-o-folder');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.category_details'))
                    ->schema([
                        Infolists\Components\TextEntry::make('key')
                            ->fontFamily('mono')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('icon')
                            ->badge(),
                        Infolists\Components\IconEntry::make('is_active')
                            ->label(__('filament.labels.active'))
                            ->boolean(),
                        Infolists\Components\TextEntry::make('sort_order')
                            ->label(__('filament.labels.sort_order')),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make(__('filament.sections.names'))
                    ->schema([
                        Infolists\Components\TextEntry::make('name_en')
                            ->label(__('filament.labels.english')),
                        Infolists\Components\TextEntry::make('name_fr')
                            ->label(__('filament.labels.french')),
                        Infolists\Components\TextEntry::make('name_ar')
                            ->label(__('filament.labels.arabic'))
                            ->placeholder(__('filament.placeholders.not_set')),
                    ])
                    ->columns(3),

                Infolists\Components\Section::make(__('filament.sections.targeting'))
                    ->schema([
                        Infolists\Components\TextEntry::make('discipline')
                            ->badge()
                            ->color('info')
                            ->placeholder(__('filament.placeholders.all_disciplines')),
                        Infolists\Components\TextEntry::make('position')
                            ->badge()
                            ->color('purple')
                            ->placeholder(__('filament.placeholders.all_positions')),
                        Infolists\Components\TextEntry::make('goal')
                            ->badge()
                            ->color('warning')
                            ->placeholder(__('filament.placeholders.all_goals')),
                        Infolists\Components\IconEntry::make('requires_injury')
                            ->label(__('filament.labels.requires_injury'))
                            ->boolean(),
                    ])
                    ->columns(4),

                Infolists\Components\Section::make(__('filament.sections.statistics'))
                    ->schema([
                        Infolists\Components\TextEntry::make('questions_count')
                            ->label(__('filament.labels.total_questions'))
                            ->state(fn ($record) => $record->questions()->count()),
                        Infolists\Components\TextEntry::make('active_questions_count')
                            ->label(__('filament.labels.active_questions'))
                            ->state(fn ($record) => $record->activeQuestions()->count()),
                        Infolists\Components\TextEntry::make('sessions_count')
                            ->label(__('filament.labels.sessions'))
                            ->state(fn ($record) => $record->sessions()->count()),
                        Infolists\Components\TextEntry::make('completed_sessions_count')
                            ->label(__('filament.labels.completed_sessions'))
                            ->state(fn ($record) => $record->sessions()->where('status', 'completed')->count()),
                    ])
                    ->columns(4),
            ]);
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
            'index' => Pages\ListFeedbackCategories::route('/'),
            'create' => Pages\CreateFeedbackCategory::route('/create'),
            'view' => Pages\ViewFeedbackCategory::route('/{record}'),
            'edit' => Pages\EditFeedbackCategory::route('/{record}/edit'),
        ];
    }
}
