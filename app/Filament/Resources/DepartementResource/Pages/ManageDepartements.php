<?php

namespace App\Filament\Resources\DepartementResource\Pages;

use Filament\Actions;
use App\Enums\PermissionsClass;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\DepartementResource;

class ManageDepartements extends ManageRecords
{
    protected static string $resource = DepartementResource::class;

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

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }


}
