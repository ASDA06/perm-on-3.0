<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Service;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $loggedService = Service::where('id', auth()->user()->service_id)->first();

        $usersInADept = User::whereHas('service.departement', function ($query) use ($loggedService) {
            $query->where('id', $loggedService->departement_id);
        })->count();

        $servicesInADept  = Service::whereHas('departement', function ($query) use ($loggedService) {
            $query->where('id', $loggedService->departement_id);
        })->count();


        return [
            Stat::make('Agents du département', $usersInADept)
                ->chart([mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50)])
                ->color("amber"),
            Stat::make('Date', today()->translatedFormat('l d M Y'))
                ->chart([mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50)])
                ->color("teal"),
            Stat::make('Services', $servicesInADept)
                ->chart([mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50), mt_rand(1, 50)])
                ->color("Cyan"),
                

        ];
    }
}
