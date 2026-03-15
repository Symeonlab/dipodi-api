<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\InterestResource\Pages;
use App\Models\Interest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class InterestResource extends Resource
{
    protected static ?string $model = Interest::class;
    protected static ?string $navigationIcon = 'heroicon-o-heart';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'key';

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.app_configuration');
    }

    public static function getNavigationLabel(): string
    {
        return __('filament.resources.interests');
    }

    public static function getPluralModelLabel(): string
    {
        return __('filament.resources.interests');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['key', 'name_en', 'name_fr', 'icon'];
    }

    public static function getGlobalSearchResultDetails(\Illuminate\Database\Eloquent\Model $record): array
    {
        return [
            'English' => $record->name_en,
            'Icon' => $record->icon,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(__('filament.sections.interest_details'))
                    ->description(__('filament.sections.interest_details_desc'))
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Forms\Components\TextInput::make('key')
                            ->label(__('filament.labels.unique_key'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder(__('filament.placeholders.interest_key'))
                            ->helperText(__('filament.helper.unique_key')),
                        Forms\Components\TextInput::make('icon')
                            ->label(__('filament.labels.icon'))
                            ->required()
                            ->placeholder(__('filament.placeholders.interest_icon'))
                            ->helperText(__('filament.helper.icon_identifier')),
                    ])
                    ->columns(2),

                Forms\Components\Section::make(__('filament.sections.translations'))
                    ->description(__('filament.helper.translations_api'))
                    ->icon('heroicon-o-language')
                    ->schema([
                        Forms\Components\TextInput::make('name_en')
                            ->label(__('filament.labels.english'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_fr')
                            ->label(__('filament.labels.french'))
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('name_ar')
                            ->label(__('filament.labels.arabic'))
                            ->required()
                            ->maxLength(255)
                            ->extraAttributes(['dir' => 'rtl']),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label(__('filament.labels.icon'))
                    ->badge()
                    ->color('info')
                    ->icon(fn (string $state): string => match ($state) {
                        'football' => 'heroicon-o-trophy',
                        'dumbbell', 'fitness' => 'heroicon-o-fire',
                        'heart', 'nutrition' => 'heroicon-o-heart',
                        'run', 'cardio' => 'heroicon-o-bolt',
                        default => 'heroicon-o-star',
                    }),
                Tables\Columns\TextColumn::make('key')
                    ->label(__('filament.labels.key'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Key copied')
                    ->fontFamily('mono'),
                Tables\Columns\TextColumn::make('name_en')
                    ->label(__('filament.labels.english'))
                    ->searchable()
                    ->weight('medium'),
                Tables\Columns\TextColumn::make('name_fr')
                    ->label(__('filament.labels.french'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_ar')
                    ->label(__('filament.labels.arabic'))
                    ->searchable()
                    ->alignRight()
                    ->extraAttributes(['dir' => 'rtl']),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('filament.labels.created'))
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('duplicate')
                    ->label(__('filament.actions.duplicate'))
                    ->icon('heroicon-o-document-duplicate')
                    ->color('gray')
                    ->action(function (Interest $record) {
                        Interest::create([
                            'key' => $record->key . '_copy_' . time(),
                            'icon' => $record->icon,
                            'name_en' => $record->name_en . ' (Copy)',
                            'name_fr' => $record->name_fr . ' (Copie)',
                            'name_ar' => $record->name_ar,
                        ]);
                    }),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('export')
                        ->label(__('filament.actions.export_selected'))
                        ->icon('heroicon-o-arrow-down-tray')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            $csv = "Key,Icon,Name (EN),Name (FR),Name (AR)\n";
                            foreach ($records as $record) {
                                $csv .= "\"{$record->key}\",\"{$record->icon}\",\"{$record->name_en}\",\"{$record->name_fr}\",\"{$record->name_ar}\"\n";
                            }
                            return response()->streamDownload(fn () => print($csv), 'interests-export.csv');
                        }),
                ]),
            ])
            ->emptyStateHeading(__('filament.empty.interests'))
            ->emptyStateDescription(__('filament.empty.interests_desc'))
            ->emptyStateIcon('heroicon-o-heart');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('filament.sections.interest_details'))
                    ->icon('heroicon-o-heart')
                    ->schema([
                        Infolists\Components\TextEntry::make('key')
                            ->label(__('filament.labels.unique_key'))
                            ->copyable()
                            ->fontFamily('mono'),
                        Infolists\Components\TextEntry::make('icon')
                            ->badge()
                            ->color('info'),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make(__('filament.sections.translations'))
                    ->icon('heroicon-o-language')
                    ->schema([
                        Infolists\Components\TextEntry::make('name_en')
                            ->label(__('filament.labels.english')),
                        Infolists\Components\TextEntry::make('name_fr')
                            ->label(__('filament.labels.french')),
                        Infolists\Components\TextEntry::make('name_ar')
                            ->label(__('filament.labels.arabic'))
                            ->extraAttributes(['dir' => 'rtl']),
                    ])
                    ->columns(3),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInterests::route('/'),
            'create' => Pages\CreateInterest::route('/create'),
            'view' => Pages\ViewInterest::route('/{record}'),
            'edit' => Pages\EditInterest::route('/{record}/edit'),
        ];
    }
}
