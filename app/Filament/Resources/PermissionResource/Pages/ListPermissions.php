<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Filament\Actions;
use App\Enums\PermissionsClass;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PermissionResource;

class ListPermissions extends ListRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function authorizeAccess(): void
    {
        $user = auth()->user();
    
        $userPermission = $user->hasAnyPermission([
            // PermissionsClass::permissions_create()->value,
            PermissionsClass::permissions_read()->value,
            // PermissionsClass::permissions_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
