<?php
namespace App\Filament\Admin\Widgets; // Note the correct namespace
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\User;

class NewUsersChart extends ChartWidget
{
    protected static ?string $heading = 'New Users (Last 30 Days)';
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 2;
    protected function getData(): array
    {
        $data = Trend::model(User::class)->between(start: now()->subMonth(), end: now())->perDay()->count();
        return [
            'datasets' => [['label' => 'New Users', 'data' => $data->map(fn (TrendValue $value) => $value->aggregate)]],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
    protected function getType(): string { return 'line'; }
}
