<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Enums\PermissionsClass;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function authorizeAccess(): void
    {
        $user = auth()->user();
    
        $userPermission = $user->hasAnyPermission([
            
            PermissionsClass::utilisateurs_create()->value,
            PermissionsClass::utilisateurs_read()->value,
            // PermissionsClass::utilisateurs_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
