<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\HomeWorkoutRuleResource\Pages;
use App\Models\HomeWorkoutRule;
use App\Models\PlayerProfile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomeWorkoutRuleResource extends Resource
{
    protected static ?string $model = HomeWorkoutRule::class;
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?int $navigationSort = 24;

    public static function getNavigationGroup(): ?string
    {
        return __('filament.nav.programme_recovery');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('filament.sections.rule_details'))
                ->schema([
                    Forms\Components\Select::make('player_profile_id')
                        ->label(__('filament.labels.player_profile'))
                        ->options(PlayerProfile::all()->pluck('name', 'id'))
                        ->required()->native(false)->searchable(),
                    Forms\Components\Select::make('objective')
                        ->options(['perte_de_poids' => __('filament.objectives.weight_loss'), 'renforcement' => __('filament.objectives.strengthening')])
                        ->required()->native(false),
                    Forms\Components\TextInput::make('duration')->required()->placeholder(__('filament.placeholders.duration')),
                    Forms\Components\TextInput::make('exercise_count')->numeric()->required(),
                    Forms\Components\TextInput::make('circuits')->numeric()->required(),
                    Forms\Components\TextInput::make('effort_time')->required()->placeholder(__('filament.placeholders.effort_time')),
                    Forms\Components\TextInput::make('rest_time')->required()->placeholder(__('filament.placeholders.rest_time')),
                    Forms\Components\TextInput::make('recovery_time')->required()->placeholder(__('filament.placeholders.circuit_rest')),
                    Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
                ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('playerProfile.name')->label(__('filament.labels.profile'))->searchable()->sortable(),
                Tables\Columns\TextColumn::make('objective')->badge()
                    ->color(fn (string $state): string => $state === 'perte_de_poids' ? 'warning' : 'success'),
                Tables\Columns\TextColumn::make('duration'),
                Tables\Columns\TextColumn::make('exercise_count')->label(__('filament.labels.exercises')),
                Tables\Columns\TextColumn::make('circuits'),
                Tables\Columns\TextColumn::make('effort_time')->label(__('filament.labels.effort')),
                Tables\Columns\TextColumn::make('rest_time')->label(__('filament.labels.rest')),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->filters([
                Tables\Filters\SelectFilter::make('objective')
                    ->options(['perte_de_poids' => __('filament.objectives.weight_loss'), 'renforcement' => __('filament.objectives.strengthening')]),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomeWorkoutRules::route('/'),
            'create' => Pages\CreateHomeWorkoutRule::route('/create'),
            'edit' => Pages\EditHomeWorkoutRule::route('/{record}/edit'),
        ];
    }
}
