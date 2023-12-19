<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Filament\Actions;
use App\Enums\PermissionsClass;
use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }


    protected function authorizeAccess(): void
    {
        $user = auth()->user();
    
        $userPermission = $user->hasAnyPermission([
            PermissionsClass::roles_create()->value,
            PermissionsClass::roles_read()->value,
            PermissionsClass::roles_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
