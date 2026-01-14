<?php
namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\BonusWorkoutRuleResource\Pages; // <-- Make sure this is correct
use App\Models\BonusWorkoutRule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BonusWorkoutRuleResource extends Resource
{
    protected static ?string $model = BonusWorkoutRule::class;
    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationGroup = 'Workout Logic';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('level')->options(['DÉBUTANT' => 'Débutant', 'INTERMÉDIAIRE' => 'Intermédiaire', 'AVANCÉ' => 'Avancé'])->required(),
                Forms\Components\Select::make('type')->options(['ABDOS' => 'Abdos', 'POMPES' => 'Pompes', 'GAINAGE' => 'Gainage'])->required(),
                Forms\Components\TextInput::make('sets')->required(),
                Forms\Components\TextInput::make('reps')->required(),
                Forms\Components\TextInput::make('recovery')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('level')->sortable()->badge(),
            Tables\Columns\TextColumn::make('type')->sortable(),
            Tables\Columns\TextColumn::make('sets'),
            Tables\Columns\TextColumn::make('reps'),
        ])->filters([
            Tables\Filters\SelectFilter::make('level')->options(['DÉBUTANT' => 'Débutant', 'INTERMÉDIAIRE' => 'Intermédiaire', 'AVANCÉ' => 'Avancé']),
        ])->actions([Tables\Actions\EditAction::make()]);
    }

    // --- THIS IS THE FIX ---
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBonusWorkoutRules::route('/'),
            'create' => Pages\CreateBonusWorkoutRule::route('/create'),
            'edit' => Pages\EditBonusWorkoutRule::route('/{record}/edit'),
        ];
    }
    // --- END OF FIX ---
}
