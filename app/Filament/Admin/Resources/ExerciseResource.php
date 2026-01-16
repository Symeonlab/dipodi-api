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
            'Category' => $record->category,
            'Sub-Category' => $record->sub_category ?? 'N/A',
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
                            ->placeholder('e.g., QUADRICEPS 1, SPRINT EN COTE')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('category')
                            ->label('Category')
                            ->options(self::getCategoryOptions())
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(fn ($set) => $set('sub_category', null)),
                        Forms\Components\Select::make('sub_category')
                            ->label('Sub-Category')
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
                            ->label('Video URL')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://youtube.com/shorts/...')
                            ->suffixIcon('heroicon-o-play')
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('met_value')
                            ->numeric()
                            ->label('MET Value')
                            ->placeholder('e.g., 4.0, 8.0, 12.0')
                            ->helperText('Metabolic Equivalent of Task for calorie calculation')
                            ->suffix('MET'),
                    ]),

                Forms\Components\Section::make('Description')
                    ->description('Optional exercise description')
                    ->icon('heroicon-o-document-text')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->rows(3)
                            ->placeholder('Describe how to perform this exercise...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Exercise')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->badge()
                    ->color(fn (string $state): string => self::getCategoryColor($state))
                    ->icon(fn (string $state): string => self::getCategoryIcon($state))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('sub_category')
                    ->label('Sub-Category')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('met_value')
                    ->label('MET')
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
                    ->label('Favorites')
                    ->counts('favoritedByUsers')
                    ->sortable()
                    ->badge()
                    ->color('danger')
                    ->icon('heroicon-o-heart'),
                Tables\Columns\IconColumn::make('video_url')
                    ->label('Video')
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
                    ->label('Category')
                    ->collapsible(),
                Tables\Grouping\Group::make('sub_category')
                    ->label('Sub-Category')
                    ->collapsible(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->label('Category')
                    ->options(self::getCategoryOptions())
                    ->multiple()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('sub_category')
                    ->label('Sub-Category')
                    ->options(fn () => Exercise::query()->distinct()->whereNotNull('sub_category')->pluck('sub_category', 'sub_category')->toArray())
                    ->multiple()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('category_type')
                    ->label('Exercise Type')
                    ->options([
                        'kine' => 'Kine (Mobility & Renforcement)',
                        'maison' => 'Home Workouts',
                        'bonus' => 'Bonus (Abs, Push-ups, Planks)',
                        'cardio' => 'Cardio Equipment',
                        'musculation' => 'Musculation (Gym)',
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
                    ->label('Has Video')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('video_url')),
                Tables\Filters\Filter::make('high_intensity')
                    ->label('High Intensity (MET >= 8)')
                    ->query(fn (Builder $query): Builder => $query->where('met_value', '>=', 8)),
            ])
            ->actions([
                Tables\Actions\Action::make('watch')
                    ->label('Watch')
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
                        ->label('Update Category')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('category')
                                ->label('New Category')
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
                        ->label('Export Selected')
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
            ->emptyStateHeading('No exercises yet')
            ->emptyStateDescription('Add exercises with video links for your training programs.')
            ->emptyStateIcon('heroicon-o-play-circle');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Exercise Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Name')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('category')
                            ->label('Category')
                            ->badge(),
                        Infolists\Components\TextEntry::make('sub_category')
                            ->label('Sub-Category')
                            ->badge(),
                        Infolists\Components\TextEntry::make('met_value')
                            ->label('MET Value')
                            ->badge()
                            ->suffix(' MET'),
                    ])
                    ->columns(2),
                Infolists\Components\Section::make('Video')
                    ->schema([
                        Infolists\Components\TextEntry::make('video_url')
                            ->label('Video URL')
                            ->url(fn ($state) => $state)
                            ->openUrlInNewTab()
                            ->icon('heroicon-o-play'),
                    ])
                    ->visible(fn ($record) => !empty($record->video_url)),
                Infolists\Components\Section::make('Description')
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
