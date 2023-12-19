<?php

namespace App\Filament\Resources\RoleResource\Pages;

use Filament\Actions;
use App\Enums\PermissionsClass;
use App\Filament\Resources\RoleResource;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

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
            PermissionsClass::roles_read()->value,
            PermissionsClass::roles_update()->value,
            PermissionsClass::roles_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
