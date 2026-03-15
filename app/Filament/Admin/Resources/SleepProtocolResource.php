<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SleepProtocolResource\Pages;
use App\Models\SleepProtocol;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SleepProtocolResource extends Resource
{
    protected static ?string $model = SleepProtocol::class;
    protected static ?string $navigationIcon = 'heroicon-o-moon';
    protected static ?int $navigationSort = 21;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.programme_recovery');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.sections.protocol_details'))
                ->schema([
                    Forms\Components\TextInput::make('condition_key')->required()->unique(ignoreRecord: true),
                    Forms\Components\Select::make('category')
                        ->options(['injury' => __('filament.protocol_types.injury'), 'medical' => __('filament.protocol_types.medical'), 'recovery' => __('filament.protocol_types.recovery')])
                        ->required()->native(false),
                    Forms\Components\TextInput::make('cycles_min')->numeric()->required(),
                    Forms\Components\TextInput::make('cycles_max')->numeric()->required(),
                    Forms\Components\TextInput::make('total_sleep')->required()->placeholder(__('filament.placeholders.sleep_duration')),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ])->columns(3),
            Forms\Components\Section::make(__('filament.labels.english'))->schema([
                Forms\Components\TextInput::make('condition_name_en')->label(__('filament.labels.condition_name'))->required(),
                Forms\Components\Textarea::make('objective_en')->label(__('filament.labels.objective'))->rows(2),
            ])->columns(2),
            Forms\Components\Section::make(__('filament.labels.french'))->schema([
                Forms\Components\TextInput::make('condition_name_fr')->label(__('filament.labels.condition_name'))->required(),
                Forms\Components\Textarea::make('objective_fr')->label(__('filament.labels.objective'))->rows(2),
            ])->columns(2),
            Forms\Components\Section::make(__('filament.labels.arabic'))->schema([
                Forms\Components\TextInput::make('condition_name_ar')->label(__('filament.labels.condition_name'))->required(),
                Forms\Components\Textarea::make('objective_ar')->label(__('filament.labels.objective'))->rows(2),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('condition_key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('condition_name_en')->label(__('filament.labels.condition'))->searchable(),
                Tables\Columns\TextColumn::make('cycles_min')->label(__('filament.labels.min_cycles')),
                Tables\Columns\TextColumn::make('cycles_max')->label(__('filament.labels.max_cycles')),
                Tables\Columns\TextColumn::make('total_sleep'),
                Tables\Columns\TextColumn::make('category')->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'injury' => 'danger', 'medical' => 'warning', 'recovery' => 'success', default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(['injury' => __('filament.protocol_types.injury'), 'medical' => __('filament.protocol_types.medical'), 'recovery' => __('filament.protocol_types.recovery')]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSleepProtocols::route('/'),
            'create' => Pages\CreateSleepProtocol::route('/create'),
            'edit' => Pages\EditSleepProtocol::route('/{record}/edit'),
        ];
    }
}
