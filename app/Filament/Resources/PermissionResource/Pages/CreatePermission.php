<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Filament\Actions;
use App\Enums\PermissionsClass;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PermissionResource;

class CreatePermission extends CreateRecord
{
    protected static string $resource = PermissionResource::class;

    protected function authorizeAccess(): void
    {
        $user = auth()->user();
    
        $userPermission = $user->hasAnyPermission([
            
            PermissionsClass::permissions_create()->value,
            PermissionsClass::permissions_read()->value,
            // PermissionsClass::utilisateurs_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
