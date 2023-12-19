<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use App\Enums\PermissionsClass;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function authorizeAccess(): void
    {
        $user = auth()->user();
    
        $userPermission = $user->hasAnyPermission([
            // PermissionsClass::utilisateurs_create()->value,
            PermissionsClass::utilisateurs_read()->value,
            PermissionsClass::utilisateurs_update()->value,
            PermissionsClass::utilisateurs_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
