<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ExerciseResource\Pages;
use App\Models\Exercise;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ExerciseResource extends Resource
{
    protected static ?string $model = Exercise::class;
    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.content_management');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.exercises');
    }

    public static function getModelLabel(): string
    {
        return __('filament.labels.exercise');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.exercises');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'category', 'sub_category'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            __('filament.labels.category') => $record->category,
            __('filament.labels.sub_category') => $record->sub_category ?? 'N/A',
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
                Forms\Components\Section::make(__('filament.sections.exercise_info'))
                    ->description(__('filament.sections.basic_details'))
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.exercise_name'))
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category')
                            ->label(__('filament.labels.category'))
                            ->options(self::getCategoryOptions())
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn ($set) => $set('sub_category', null)),
                        Forms\Components\Select::make('sub_category')
                            ->label(__('filament.labels.sub_category'))
                            ->options(fn (Forms\Get $get): array => self::getSubCategoryOptions($get('category')))
                            ->searchable()
                            ->native(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.video_intensity'))
                    ->description(__('filament.sections.video_intensity_desc'))
                    ->icon('heroicon-o-video-camera')
                    ->schema([
                        Forms\Components\TextInput::make('video_url')
                            ->label(__('filament.labels.video_url'))
                            ->url()
                            ->maxLength(255)
                            ->placeholder(__('filament.placeholders.video_url'))
                            ->suffixIcon('heroicon-o-play')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('met_value')
                            ->numeric()
                            ->label(__('filament.labels.met_value'))
                            ->placeholder(__('filament.placeholders.met_value'))
                            ->helperText(__('filament.helper.met_value'))
                            ->suffix('MET'),
                    ]),

                Forms\Components\Section::make(__('filament.sections.description'))
                    ->description(__('filament.sections.description_desc'))
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder(__('filament.placeholders.description'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('filament.labels.exercise'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->label(__('filament.labels.category'))
                    ->badge()
                    ->color(fn (string $state): string => self::getCategoryColor($state))
                    ->icon(fn (string $state): string => self::getCategoryIcon($state))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sub_category')
                    ->label(__('filament.labels.sub_category'))
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('met_value')
                    ->label(__('filament.labels.met_value'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state >= 10 => 'danger',
                        $state >= 6 => 'warning',
                        $state >= 3 => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('favoritedByUsers_count')
                    ->label(__('filament.labels.favorites'))
                    ->counts('favoritedByUsers')
                    ->sortable()
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-o-heart'),
                Tables\Columns\IconColumn::make('video_url')
                    ->label(__('filament.labels.video'))
                    ->boolean()
                    ->trueIcon('heroicon-o-play-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
            ])
            ->defaultSort('category')
            ->defaultGroup('category')
            ->groups([
                Tables\Grouping\Group::make('category')
                    ->label(__('filament.labels.category'))
                    ->collapsible(),
                Tables\Grouping\Group::make('sub_category')
                    ->label(__('filament.labels.sub_category'))
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label(__('filament.labels.category'))
                    ->options(self::getCategoryOptions())
                    ->multiple()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('sub_category')
                    ->label(__('filament.labels.sub_category'))
                    ->options(fn () => Exercise::query()->distinct()->whereNotNull('sub_category')->pluck('sub_category', 'sub_category')->toArray())
                    ->multiple()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('category_type')
                    ->label(__('filament.filters.exercise_type'))
                    ->options([
                        'kine' => __('filament.categories.kine_renforcement') . ' & ' . __('filament.categories.kine_mobilite'),
                        'maison' => __('filament.categories.maison'),
                        'bonus' => __('filament.categories.bonus'),
                        'cardio' => __('filament.categories.cardio'),
                        'musculation' => __('filament.categories.musculation'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) return $query;

                        return match ($data['value']) {
                            'kine' => $query->where('category', 'LIKE', 'KINE%'),
                            'maison' => $query->where('category', 'LIKE', '%MAISON%'),
                            'bonus' => $query->where('category', 'LIKE', '%BONUS%'),
                            'cardio' => $query->where('category', 'LIKE', '%CARDIO%'),
                            'musculation' => $query->where('category', '=', 'MUSCULATION'),
                            default => $query,
                        };
                    }),
                Tables\Filters\Filter::make('has_video')
                    ->label(__('filament.filters.has_video'))
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('video_url')),
                Tables\Filters\Filter::make('high_intensity')
                    ->label(__('filament.labels.high_intensity'))
                    ->query(fn (Builder $query): Builder => $query->where('met_value', '>=', 8)),
            ])
            ->actions([
                Tables\Actions\Action::make('watch')
                    ->label(__('filament.actions.watch'))
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->url(fn (Exercise $record): ?string => $record->video_url)
                    ->openUrlInNewTab()
                    ->visible(fn (Exercise $record): bool => !empty($record->video_url)),
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
                                ->label(__('filament.labels.category'))
                                ->options(self::getCategoryOptions())
                                ->required(),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            $records->each(function ($record) use ($data) {
                                $record->update(['category' => $data['category']]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')
                        ->label(__('filament.actions.export_selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csv = "Name,Category,Sub-Category,MET,Video URL\n";
                            foreach ($records as $record) {
                                $csv .= "\"{$record->name}\",\"{$record->category}\",\"{$record->sub_category}\",\"{$record->met_value}\",\"{$record->video_url}\"\n";
                            }
                            return response()->streamDownload(function () use ($csv) {
                                echo $csv;
                            }, 'exercises-export.csv');
                        }),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.exercises'))
            ->emptyStateDescription(__('filament.empty.exercises_desc'))
            ->emptyStateIcon('heroicon-o-play-circle');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.exercise_info'))
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label(__('filament.labels.name'))
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('category')
                            ->label(__('filament.labels.category'))
                            ->badge(),
                        Infolists\Components\TextEntry::make('sub_category')
                            ->label(__('filament.labels.sub_category'))
                            ->badge(),
                        Infolists\Components\TextEntry::make('met_value')
                            ->label(__('filament.labels.met_value'))
                            ->badge()
                            ->suffix(' MET'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make(__('filament.labels.video'))
                    ->schema([
                        Infolists\Components\TextEntry::make('video_url')
                            ->label(__('filament.labels.video_url'))
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-play'),
                    ])
                    ->visible(fn ($record) => !empty($record->video_url)),
                Infolists\Components\Section::make(__('filament.sections.description'))
                    ->schema([
                        Infolists\Components\TextEntry::make('description')
                            ->label('')
                            ->markdown(),
                    ])
                    ->visible(fn ($record) => !empty($record->description)),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExercises::route('/'),
            'create' => Pages\CreateExercise::route('/create'),
            'edit' => Pages\EditExercise::route('/{record}/edit'),
            'view' => Pages\ViewExercise::route('/{record}'),
        ];
    }

    private static function getCategoryOptions(): array
    {
        return [
            'MUSCULATION' => 'Musculation (Gym)',
            'BONUS' => 'Bonus (Abdos, Pompes, Gainage)',
            'MAISON' => 'Maison (Home Workouts)',
            'KINE RENFORCEMENT' => 'Kine Renforcement',
            'KINE MOBILITÉ' => 'Kine Mobilité',
            'CARDIO' => 'Cardio',
        ];
    }

    private static function getSubCategoryOptions(?string $category): array
    {
        if (!$category) return [];

        $subCategories = [
            'MUSCULATION' => [
                'BRAS' => 'Bras',
                'DOS' => 'Dos',
                'ÉPAULES' => 'Épaules',
                'PECTORAUX' => 'Pectoraux',
                'QUADRICEPS' => 'Quadriceps',
                'ISHIOS' => 'Ishios Jambiers',
                'MOLLETS' => 'Mollets',
                'FESSIERS' => 'Fessiers',
            ],
            'BONUS' => [
                'ABDOS' => 'Abdos',
                'POMPES' => 'Pompes',
                'GAINAGE' => 'Gainage',
            ],
            'MAISON' => [
                'PERTE DE POIDS' => 'Perte de Poids',
                'RENFORCEMENT' => 'Renforcement',
            ],
            'KINE RENFORCEMENT' => [
                'QUADRICEPS' => 'Quadriceps',
                'PSOAS' => 'Psoas / Fléchisseurs Hanches',
                'PIEDS' => 'Pieds',
                'MOYEN FESSIERS' => 'Moyen Fessiers',
                'MOLLETS' => 'Mollets',
                'ISHIOS JAMBIERS' => 'Ishios Jambiers',
                'FESSIERS' => 'Fessiers',
                'CHEVILLES' => 'Chevilles',
                'ADDUCTEURS' => 'Adducteurs',
            ],
            'KINE MOBILITÉ' => [
                'PIEDS' => 'Pieds / Voûte Plantaire',
                'HANCHES' => 'Hanches',
                'GENOUX' => 'Genoux',
                'CHEVILLES' => 'Chevilles',
                'ISHIOS JAMBIERS' => 'Ishios Jambiers',
            ],
            'CARDIO' => [
                'CARDIO' => 'Cardio Général',
                'ENDURANCE' => 'Endurance',
                'HIIT' => 'HIIT',
            ],
        ];

        return $subCategories[$category] ?? [];
    }

    private static function getCategoryColor(string $category): string
    {
        return match ($category) {
            'MUSCULATION' => 'primary',
            'BONUS' => 'danger',
            'MAISON' => 'warning',
            'KINE RENFORCEMENT' => 'success',
            'KINE MOBILITÉ' => 'info',
            'CARDIO' => 'purple',
            default => 'gray',
        };
    }

    private static function getCategoryIcon(string $category): string
    {
        return match ($category) {
            'MUSCULATION' => 'heroicon-o-scale',
            'BONUS' => 'heroicon-o-fire',
            'MAISON' => 'heroicon-o-home',
            'KINE RENFORCEMENT' => 'heroicon-o-heart',
            'KINE MOBILITÉ' => 'heroicon-o-arrow-path',
            'CARDIO' => 'heroicon-o-bolt',
            default => 'heroicon-o-sparkles',
        };
    }
}
