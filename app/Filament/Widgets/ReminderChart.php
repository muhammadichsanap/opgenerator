<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class ReminderChart extends ChartWidget
{
    protected static ?string $heading = 'Bar Chart Pendapatan/hari';

    protected function getData(): array
    {
        $data = \DB::table('reminders')
            ->select(\DB::raw('DATE(created_at) as date'), \DB::raw('SUM(total) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $labels = $data->pluck('date')->toArray();
        $totals = $data->pluck('total')->toArray();

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Total Pendapatan',
                    'data' => $totals,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
