<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use Filament\Actions;
use App\Enums\PermissionsClass;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ServiceResource;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;


    protected function authorizeAccess(): void
    {
        $user = auth()->user();

        $userPermission = $user->hasAnyPermission([

            PermissionsClass::services_create()->value,
            PermissionsClass::services_read()->value,
            // PermissionsClass::utilisateurs_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);

        abort_if(!$userPermission, 403, __("Vous n'avez pas access à cette page"));
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
