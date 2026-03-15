<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\IntensityZoneResource\Pages;
use App\Models\IntensityZone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IntensityZoneResource extends Resource
{
    protected static ?string $model = IntensityZone::class;
    protected static ?string $navigationIcon = 'heroicon-o-signal';
    protected static ?int $navigationSort = 20;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.programme_recovery');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.sections.zone_details'))
                ->schema([
                    Forms\Components\Select::make('color')
                        ->options(['blue' => __('filament.zone_colors.blue'), 'green' => __('filament.zone_colors.green'), 'yellow' => __('filament.zone_colors.yellow'), 'orange' => __('filament.zone_colors.orange'), 'red' => __('filament.zone_colors.red')])
                        ->required()->native(false),
                    Forms\Components\TextInput::make('intensity_range')->placeholder(__('filament.placeholders.intensity_range')),
                    Forms\Components\TextInput::make('rpe_min')->numeric(),
                    Forms\Components\TextInput::make('rpe_max')->numeric(),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ])->columns(3),
            Forms\Components\Section::make(__('filament.labels.english'))->schema([
                Forms\Components\TextInput::make('name_en')->label(__('filament.labels.name'))->required(),
                Forms\Components\Textarea::make('description_en')->label(__('filament.labels.description'))->rows(2),
            ])->columns(2),
            Forms\Components\Section::make(__('filament.labels.french'))->schema([
                Forms\Components\TextInput::make('name_fr')->label(__('filament.labels.name'))->required(),
                Forms\Components\Textarea::make('description_fr')->label(__('filament.labels.description'))->rows(2),
            ])->columns(2),
            Forms\Components\Section::make(__('filament.labels.arabic'))->schema([
                Forms\Components\TextInput::make('name_ar')->label(__('filament.labels.name'))->required(),
                Forms\Components\Textarea::make('description_ar')->label(__('filament.labels.description'))->rows(2),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('color')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'red' => 'danger', 'orange' => 'warning', 'yellow' => 'warning',
                        'green' => 'success', 'blue' => 'info', default => 'gray',
                    })->sortable(),
                Tables\Columns\TextColumn::make('name_en')->label(__('filament.labels.name'))->searchable(),
                Tables\Columns\TextColumn::make('intensity_range'),
                Tables\Columns\TextColumn::make('rpe_min')->label(__('filament.labels.rpe_min')),
                Tables\Columns\TextColumn::make('rpe_max')->label(__('filament.labels.rpe_max')),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIntensityZones::route('/'),
            'create' => Pages\CreateIntensityZone::route('/create'),
            'edit' => Pages\EditIntensityZone::route('/{record}/edit'),
        ];
    }
}
