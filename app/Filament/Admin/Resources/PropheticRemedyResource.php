<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PropheticRemedyResource\Pages;
use App\Models\PropheticRemedy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PropheticRemedyResource extends Resource
{
    protected static ?string $model = PropheticRemedy::class;
    protected static ?string $navigationIcon = 'heroicon-o-beaker';
    protected static ?int $navigationSort = 23;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.programme_recovery');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.sections.condition_element'))
                ->schema([
                    Forms\Components\TextInput::make('condition_key')->required(),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ])->columns(2),
            Forms\Components\Section::make(__('filament.labels.english'))->schema([
                Forms\Components\TextInput::make('condition_name_en')->label(__('filament.labels.condition'))->required(),
                Forms\Components\TextInput::make('element_name_en')->label(__('filament.labels.element'))->required(),
                Forms\Components\Textarea::make('mechanism_en')->label(__('filament.labels.mechanism'))->rows(2),
                Forms\Components\Textarea::make('recipe_en')->label(__('filament.labels.recipe'))->rows(2),
            ])->columns(2),
            Forms\Components\Section::make(__('filament.labels.french'))->schema([
                Forms\Components\TextInput::make('condition_name_fr')->label(__('filament.labels.condition'))->required(),
                Forms\Components\TextInput::make('element_name_fr')->label(__('filament.labels.element'))->required(),
                Forms\Components\Textarea::make('mechanism_fr')->label(__('filament.labels.mechanism'))->rows(2),
                Forms\Components\Textarea::make('recipe_fr')->label(__('filament.labels.recipe'))->rows(2),
            ])->columns(2),
            Forms\Components\Section::make(__('filament.labels.arabic'))->schema([
                Forms\Components\TextInput::make('condition_name_ar')->label(__('filament.labels.condition'))->required(),
                Forms\Components\TextInput::make('element_name_ar')->label(__('filament.labels.element'))->required(),
                Forms\Components\Textarea::make('mechanism_ar')->label(__('filament.labels.mechanism'))->rows(2),
                Forms\Components\Textarea::make('recipe_ar')->label(__('filament.labels.recipe'))->rows(2),
            ])->columns(2),
            Forms\Components\Section::make(__('filament.sections.notes'))->schema([
                Forms\Components\Textarea::make('notes')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('condition_key')->badge()->color('info')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('condition_name_en')->label(__('filament.labels.condition'))->searchable(),
                Tables\Columns\TextColumn::make('element_name_en')->label(__('filament.labels.element'))->searchable(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('condition_key')
            ->filters([
                Tables\Filters\SelectFilter::make('condition_key')
                    ->options(fn () => PropheticRemedy::distinct()->pluck('condition_key', 'condition_key')->toArray()),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPropheticRemedies::route('/'),
            'create' => Pages\CreatePropheticRemedy::route('/create'),
            'edit' => Pages\EditPropheticRemedy::route('/{record}/edit'),
        ];
    }
}
