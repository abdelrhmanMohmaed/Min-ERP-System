<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\Employee;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'All' => ListRecords\Tab::make(),
            'This Week' => ListRecords\Tab::make()
                ->modifyQueryUsing(fn (Builder$query) => $query->where('date_hired','>=',now()->subWeek()))
                ->badge(Employee::query()->where('date_hired','>=',now()->subWeek())->count()),
            'This Month' => ListRecords\Tab::make()
                ->modifyQueryUsing(fn (Builder$query) => $query->where('date_hired','>=',now()->subMonth()))
                ->badge(Employee::query()->where('date_hired','>=',now()->subMonth())->count()),
            'This Year' => ListRecords\Tab::make()
                ->modifyQueryUsing(fn (Builder$query) => $query->where('date_hired','>=',now()->subYear()))
                ->badge(Employee::query()->where('date_hired','>=',now()->subYear())->count()),
        ];
    }
}
