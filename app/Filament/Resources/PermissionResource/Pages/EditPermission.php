<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use Filament\Actions;
use App\Enums\PermissionsClass;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PermissionResource;

class EditPermission extends EditRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function authorizeAccess(): void
    {
        $user = auth()->user();
    
        $userPermission = $user->hasAnyPermission([
            // PermissionsClass::utilisateurs_create()->value,
            PermissionsClass::permissions_read()->value,
            PermissionsClass::permissions_update()->value,
            PermissionsClass::permissions_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
