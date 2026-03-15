<?php
namespace App\Filament\Admin\Widgets; // Note the correct namespace
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use App\Models\User;

class NewUsersChart extends ChartWidget
{
    protected static ?string $heading = null;
    protected static ?string $maxHeight = '300px';
    protected static ?int $sort = 2;
    public function getHeading(): ?string
    {
        return __('filament.widgets.new_users_30_days');
    }

    protected function getData(): array
    {
        $data = Trend::model(User::class)->between(start: now()->subMonth(), end: now())->perDay()->count();
        return [
            'datasets' => [['label' => __('filament.widgets.new_users'), 'data' => $data->map(fn (TrendValue $value) => $value->aggregate)]],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }
    protected function getType(): string { return 'line'; }
}
