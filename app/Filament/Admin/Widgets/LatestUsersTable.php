<?php
namespace App\Filament\Admin\Widgets;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\User;

class LatestUsersTable extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = null;
    public function getTableHeading(): ?string
    {
        return __('filament.widgets.latest_user_registrations');
    }

    public function table(Table $table): Table
    {
        return $table->query(User::query()->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('filament.labels.name'))->searchable(),
                Tables\Columns\TextColumn::make('email')->label(__('filament.labels.email'))->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('filament.labels.created_at'))->dateTime()->sortable(),
            ]);
    }
}
