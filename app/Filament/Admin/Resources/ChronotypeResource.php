<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ChronotypeResource\Pages;
use App\Models\Chronotype;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChronotypeResource extends Resource
{
    protected static ?string $model = Chronotype::class;
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?int $navigationSort = 22;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.programme_recovery');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.sections.chronotype_details'))
                ->schema([
                    Forms\Components\TextInput::make('key')->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('icon')->placeholder(__('filament.placeholders.sf_symbol')),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ])->columns(3),
            Forms\Components\Section::make(__('filament.sections.schedule'))
                ->schema([
                    Forms\Components\TimePicker::make('wake_time')->required(),
                    Forms\Components\TimePicker::make('peak_start')->required(),
                    Forms\Components\TimePicker::make('peak_end')->required(),
                    Forms\Components\TimePicker::make('bedtime')->required(),
                ])->columns(4),
            Forms\Components\Section::make(__('filament.labels.english'))->schema([
                Forms\Components\TextInput::make('name_en')->label(__('filament.labels.name'))->required(),
                Forms\Components\Textarea::make('description_en')->label(__('filament.labels.description'))->rows(2),
                Forms\Components\Textarea::make('character_en')->label(__('filament.labels.character'))->rows(2),
            ])->columns(3),
            Forms\Components\Section::make(__('filament.labels.french'))->schema([
                Forms\Components\TextInput::make('name_fr')->label(__('filament.labels.name'))->required(),
                Forms\Components\Textarea::make('description_fr')->label(__('filament.labels.description'))->rows(2),
                Forms\Components\Textarea::make('character_fr')->label(__('filament.labels.character'))->rows(2),
            ])->columns(3),
            Forms\Components\Section::make(__('filament.labels.arabic'))->schema([
                Forms\Components\TextInput::make('name_ar')->label(__('filament.labels.name'))->required(),
                Forms\Components\Textarea::make('description_ar')->label(__('filament.labels.description'))->rows(2),
                Forms\Components\Textarea::make('character_ar')->label(__('filament.labels.character'))->rows(2),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->badge()->sortable(),
                Tables\Columns\TextColumn::make('name_en')->label(__('filament.labels.name'))->searchable(),
                Tables\Columns\TextColumn::make('wake_time'),
                Tables\Columns\TextColumn::make('peak_start'),
                Tables\Columns\TextColumn::make('peak_end'),
                Tables\Columns\TextColumn::make('bedtime'),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChronotypes::route('/'),
            'create' => Pages\CreateChronotype::route('/create'),
            'edit' => Pages\EditChronotype::route('/{record}/edit'),
        ];
    }
}
