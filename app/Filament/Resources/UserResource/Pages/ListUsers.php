<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Models\User;
use Database\Seeders\RolesPermissionsSeeder;
use Filament\Actions;
use App\Models\Service;
use App\Models\Departement;
use App\Enums\PermissionsClass;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        $actions = array();

        if (auth()->user()->hasPermissionTo(PermissionsClass::utilisateurs_create()->value)) {
            $actions = [
                Actions\CreateAction::make()
            ];

        }
        return $actions;
    }
    


    protected function getTableQuery(): ?Builder
    {
        $query = null;

        if (auth()->user()->hasRole(RolesPermissionsSeeder::SuperAdmin)) {
            $query = $this->getTableQueryForAdmin();
        } elseif (auth()->user()->hasRole(RolesPermissionsSeeder::Manager)) {
            $query = $this->getTableQueryForManager();
        }
        else  abort( 403, ("Vous n'avez pas access à cette page"));

        return $query;
    }


    protected function getTableQueryForManager(): ?Builder
    {
        $loggedUserId = auth()->user()->service_id;

        $loggedService = Service::where('id', $loggedUserId)->value('departement_id');

        return static::getResource()::getEloquentQuery()

            ->whereHas('service.departement', function ($query) use ($loggedService) {
                $query->where('id', $loggedService);
            })
            ->join('services', 'services.id', 'users.service_id')
            ->join('departements', 'departements.id', '=', 'services.departement_id')
            ->select('users.*', 'services.nom_service as service');
    }


    protected function getTableQueryForAdmin(): ?Builder
    {

        return static::getResource()::getEloquentQuery()
            ->join('services', 'services.id', 'users.service_id')
            ->join('departements', 'departements.id', '=', 'services.departement_id')
            ->select('users.*', 'services.nom_service as service');
    }




    protected function authorizeAccess(): void
    {
        $user = auth()->user();

        $userPermission = $user->hasAnyPermission([
            PermissionsClass::utilisateurs_create()->value,
            PermissionsClass::utilisateurs_read()->value,
            PermissionsClass::utilisateurs_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);

        abort_if(!$userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
