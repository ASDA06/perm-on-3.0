<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use Database\Seeders\RolesPermissionsSeeder;
use Filament\Actions;
use App\Models\Service;
use App\Enums\PermissionsClass;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ServiceResource;

class ListServices extends ListRecords
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        if (auth()->user()->hasRole(RolesPermissionsSeeder::SuperAdmin)) {
            return $this->getAdminTableQuery();
        } else {
            return $this->getUserTableQuery();
        }
    }


    protected function authorizeAccess(): void
    {
        $user = auth()->user();

        $userPermission = $user->hasAnyPermission([
            PermissionsClass::services_create()->value,
            PermissionsClass::services_read()->value,
            PermissionsClass::services_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);

        abort_if(!$userPermission, 403, __("Vous n'avez pas access à cette page"));
    }

    private function getUserTableQuery()
    {
        $loggedUserId = auth()->user()->service_id;

        $loggedService = Service::where('id', $loggedUserId)->first();

        return static::getResource()::getEloquentQuery()
            ->whereHas('departement', function ($query) use ($loggedService) {
                $query->where('id', $loggedService->departement_id);
            })
            ->join('departements', 'services.departement_id', 'departements.id')
            ->select('services.*', 'nom_departement');
    }


    private function getAdminTableQuery()
    {
        return Service::join('departements', 'services.departement_id', 'departements.id')
            ->select('services.*', 'nom_departement');
    }


}
