<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use Filament\Widgets\ChartWidget;

class EmployeeAdminChart extends ChartWidget
{
    protected static ?string $heading = 'Chart';
    protected static ?int $sort =  3;

    protected function getData(): array
    {
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' => [0, 10, 5, 2, 21, 32, 45, 74, 65, 45, 77, 89],
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        ];
    }
# Filament Demo App (Min ERP System)

A demo application to illustrate how Filament Admin works for ERP System.

    protected function getType(): string
    {
        return 'line';
    }
}
