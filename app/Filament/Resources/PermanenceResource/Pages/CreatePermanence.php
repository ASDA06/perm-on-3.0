<?php

namespace App\Filament\Resources\PermanenceResource\Pages;

use Carbon\Carbon;
use App\Models\User;
use Filament\Actions;
use App\Models\Service;
use App\Enums\PermissionsClass;
use App\Functions\DateFunction;
use Filament\Notifications\Notification;
use Filament\Notifications\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PermanenceResource;

class CreatePermanence extends CreateRecord
{
    protected static string $resource = PermanenceResource::class;


    protected function authorizeAccess(): void
    {
        $user = auth()->user();

        $userPermission = $user->hasAnyPermission([

            PermissionsClass::permanences_create()->value,
            PermissionsClass::permanences_read()->value,
            // PermissionsClass::utilisateurs_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);

        abort_if(!$userPermission, 403, __("Vous n'avez pas access à cette page"));
    }

   

}
