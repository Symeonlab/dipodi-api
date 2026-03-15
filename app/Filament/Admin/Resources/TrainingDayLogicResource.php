<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\TrainingDayLogicResource\Pages;
use App\Models\TrainingDayLogic;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TrainingDayLogicResource extends Resource
{
    protected static ?string $model = TrainingDayLogic::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 25;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.programme_recovery');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.sections.training_day_distribution'))
                ->schema([
                    Forms\Components\TextInput::make('total_days')
                        ->label(__('filament.labels.total_training_days'))
                        ->numeric()->required()->minValue(1)->maxValue(7)
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('theme_principal_count')
                        ->label(__('filament.labels.principal_themes'))
                        ->numeric()->required(),
                    Forms\Components\TextInput::make('random_count')
                        ->label(__('filament.labels.random_themes'))
                        ->numeric()->required(),
                    Forms\Components\TextInput::make('alt_theme_count')
                        ->label(__('filament.labels.alt_themes'))
                        ->numeric()->default(0),
                    Forms\Components\TextInput::make('alt_random_count')
                        ->label(__('filament.labels.alt_random'))
                        ->numeric()->default(0),
                ])->columns(5),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('total_days')->label(__('filament.labels.days_week'))->badge()->color('primary')->sortable(),
                Tables\Columns\TextColumn::make('theme_principal_count')->label(__('filament.labels.principal')),
                Tables\Columns\TextColumn::make('random_count')->label(__('filament.labels.random')),
                Tables\Columns\TextColumn::make('alt_theme_count')->label(__('filament.labels.alt_themes')),
                Tables\Columns\TextColumn::make('alt_random_count')->label(__('filament.labels.alt_random')),
            ])
            ->defaultSort('total_days')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrainingDayLogics::route('/'),
            'create' => Pages\CreateTrainingDayLogic::route('/create'),
            'edit' => Pages\EditTrainingDayLogic::route('/{record}/edit'),
        ];
    }
}
