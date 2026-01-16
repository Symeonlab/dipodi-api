<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\OnboardingOptionResource\Pages;
use App\Models\OnboardingOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OnboardingOptionResource extends Resource
{
    protected static ?string $model = OnboardingOption::class;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'key';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.app_configuration');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.onboarding_options');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.onboarding_options');
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['key', 'type', 'name_en', 'name_fr'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'Type' => self::getTypeOptions()[$record->type] ?? $record->type,
            'English' => $record->name_en,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'primary';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Option Identity')
                    ->description('Define the type and key for this option')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('Option Type')
                            ->options(self::getTypeOptions())
                            ->required()
                            ->native(false)
                            ->searchable()
                            ->helperText('The category this option belongs to'),
                        Forms\Components\TextInput::make('key')
                            ->label('Unique Key')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., goal.lose_weight, discipline.football')
                            ->helperText('Used by the app to identify this option')
                            ->unique(ignoreRecord: true),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Translations')
                    ->description('Provide names in all supported languages')
                    ->icon('heroicon-o-language')
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label('English')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Name in English'),
                        Forms\Components\TextInput::make('name_fr')
                            ->label('Francais')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Nom en francais'),
                        Forms\Components\TextInput::make('name_ar')
                            ->label('Arabic')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('...')
                            ->extraAttributes(['dir' => 'rtl']),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => self::getTypeColor($state))
                    ->icon(fn (string $state): string => self::getTypeIcon($state))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->label('Key')
                    ->searchable()
                    ->copyable()
                    ->fontFamily('mono')
                    ->size('sm'),
                Tables\Columns\TextColumn::make('name_en')
                    ->label('English')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('name_fr')
                    ->label('Francais')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label('Arabic')
                    ->searchable()
                    ->limit(30)
                    ->alignRight()
                    ->extraAttributes(['dir' => 'rtl']),
            ])
            ->defaultSort('type')
            ->defaultGroup('type')
            ->groups([
                Tables\Grouping\Group::make('type')
                    ->label('Option Type')
                    ->collapsible()
                    ->titlePrefixedWithLabel(false),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options(self::getTypeOptions())
                    ->multiple()
                    ->searchable(),
                Tables\Filters\SelectFilter::make('category_group')
                    ->label('Category')
                    ->options([
                        'sport' => 'Sport & Discipline',
                        'profile' => 'Player Profiles',
                        'health' => 'Health & Injury',
                        'nutrition' => 'Nutrition & Diet',
                        'preferences' => 'User Preferences',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) return $query;

                        $typeGroups = [
                            'sport' => ['discipline', 'level', 'goal', 'location', 'training_days'],
                            'profile' => ['football_position', 'fitness_profile_female', 'fitness_profile_male', 'padel_position', 'padel_player_type'],
                            'health' => ['injury_location', 'morphology', 'activity_level', 'sportif_status', 'hormonal'],
                            'nutrition' => ['breakfast_preference', 'bad_habit', 'snacking_frequency', 'food_consumption_frequency', 'meals_per_day', 'dietary_preference'],
                            'preferences' => ['gender', 'musculation_objective'],
                        ];

                        if (isset($typeGroups[$data['value']])) {
                            return $query->whereIn('type', $typeGroups[$data['value']]);
                        }
                        return $query;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('duplicate')
                    ->label('Duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (OnboardingOption $record) {
                        OnboardingOption::create([
                            'type' => $record->type,
                            'key' => $record->key . '_copy_' . time(),
                            'name_en' => $record->name_en . ' (Copy)',
                            'name_fr' => $record->name_fr . ' (Copie)',
                            'name_ar' => $record->name_ar,
                        ]);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('updateType')
                        ->label('Update Type')
                        ->icon('heroicon-o-tag')
                        ->form([
                            Forms\Components\Select::make('type')
                                ->label('New Type')
                                ->options(self::getTypeOptions())
                                ->required()
                                ->searchable(),
                        ])
                        ->action(function (\Illuminate\Support\Collection $records, array $data): void {
                            $records->each(fn ($record) => $record->update(['type' => $data['type']]));
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export Selected')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csv = "Type,Key,Name (EN),Name (FR),Name (AR)\n";
                            foreach ($records as $record) {
                                $csv .= "\"{$record->type}\",\"{$record->key}\",\"{$record->name_en}\",\"{$record->name_fr}\",\"{$record->name_ar}\"\n";
                            }
                            return response()->streamDownload(fn () => print($csv), 'onboarding-options-export.csv');
                        }),
                ]),
            ])
            ->emptyStateHeading('No onboarding options')
            ->emptyStateDescription('Create options for the user onboarding flow.')
            ->emptyStateIcon('heroicon-o-queue-list');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOnboardingOptions::route('/'),
            'create' => Pages\CreateOnboardingOption::route('/create'),
            'edit' => Pages\EditOnboardingOption::route('/{record}/edit'),
        ];
    }

    private static function getTypeOptions(): array
    {
        return [
            'discipline' => 'Discipline (Sport Type)',
            'level' => 'Fitness Level',
            'goal' => 'User Goal',
            'location' => 'Training Location',
            'training_days' => 'Training Days',
            'gender' => 'Gender',
            'football_position' => 'Football Position',
            'fitness_profile_female' => 'Fitness Profile (Female)',
            'fitness_profile_male' => 'Fitness Profile (Male)',
            'padel_position' => 'Padel Position',
            'padel_player_type' => 'Padel Player Type',
            'injury_location' => 'Injury Location',
            'morphology' => 'Body Morphology',
            'activity_level' => 'Activity Level',
            'sportif_status' => 'Sportif Status',
            'breakfast_preference' => 'Breakfast Preference',
            'bad_habit' => 'Bad Habit',
            'snacking_frequency' => 'Snacking Frequency',
            'food_consumption_frequency' => 'Food Consumption',
            'meals_per_day' => 'Meals Per Day',
            'musculation_objective' => 'Musculation Objective',
            'dietary_preference' => 'Dietary Preference',
            'hormonal' => 'Hormonal Issues',
        ];
    }

    private static function getTypeColor(string $type): string
    {
        return match ($type) {
            'discipline' => 'success',
            'level' => 'info',
            'goal' => 'warning',
            'location' => 'gray',
            'training_days' => 'gray',
            'gender' => 'purple',
            'football_position' => 'success',
            'fitness_profile_female' => 'pink',
            'fitness_profile_male' => 'info',
            'padel_position' => 'warning',
            'padel_player_type' => 'warning',
            'injury_location' => 'danger',
            'morphology' => 'gray',
            'activity_level' => 'info',
            'sportif_status' => 'success',
            'breakfast_preference' => 'warning',
            'bad_habit' => 'danger',
            'snacking_frequency' => 'warning',
            'food_consumption_frequency' => 'info',
            'meals_per_day' => 'gray',
            'musculation_objective' => 'success',
            'dietary_preference' => 'info',
            'hormonal' => 'purple',
            default => 'gray',
        };
    }

    private static function getTypeIcon(string $type): string
    {
        return match ($type) {
            'discipline' => 'heroicon-o-trophy',
            'level' => 'heroicon-o-chart-bar',
            'goal' => 'heroicon-o-flag',
            'location' => 'heroicon-o-map-pin',
            'training_days' => 'heroicon-o-calendar',
            'gender' => 'heroicon-o-user',
            'football_position' => 'heroicon-o-shield-check',
            'fitness_profile_female' => 'heroicon-o-user',
            'fitness_profile_male' => 'heroicon-o-user',
            'padel_position' => 'heroicon-o-rectangle-stack',
            'padel_player_type' => 'heroicon-o-bolt',
            'injury_location' => 'heroicon-o-heart',
            'morphology' => 'heroicon-o-user-circle',
            'activity_level' => 'heroicon-o-arrow-trending-up',
            'sportif_status' => 'heroicon-o-star',
            'breakfast_preference' => 'heroicon-o-sun',
            'bad_habit' => 'heroicon-o-exclamation-triangle',
            'snacking_frequency' => 'heroicon-o-clock',
            'food_consumption_frequency' => 'heroicon-o-cake',
            'meals_per_day' => 'heroicon-o-squares-2x2',
            'musculation_objective' => 'heroicon-o-fire',
            'dietary_preference' => 'heroicon-o-clipboard-document-list',
            'hormonal' => 'heroicon-o-beaker',
            default => 'heroicon-o-question-mark-circle',
        };
    }
}
