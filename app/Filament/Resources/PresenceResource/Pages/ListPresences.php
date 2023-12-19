<?php

namespace App\Filament\Resources\PresenceResource\Pages;

use App\Models\User;
use Filament\Actions;
use App\Models\Service;
use App\Enums\PermissionsClass;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PresenceResource;

class ListPresences extends ListRecords
{
    protected static string $resource = PresenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): ?Builder
    {
        $loggedUser = User::where('id', auth()->user()->id)->value('id');

        $loggedService  = Service::where('id', auth()->user()->service_id)->value('departement_id');

        // for self only prensences
        
        // return static::getResource()::getEloquentQuery()
        //     ->whereHas('user', function ($query) use ($loggedUser) {
        //         $query->where('id', $loggedUser);
        //     })
        //     ->join('users', 'presences.user_id', 'users.id')
        //     ->select('presences.*', 'users.name as nom');

        return static::getResource()::getEloquentQuery()
        ->whereHas('user.service.departement', function ($query) use ( $loggedService) {
            $query->where('id',  $loggedService);
        })
        ->join('users', 'presences.user_id', 'users.id')
        ->select('presences.*', 'users.name as nom');

    }

    protected function authorizeAccess(): void
    {
        $user = auth()->user();
    
        $userPermission = $user->hasAnyPermission([
            PermissionsClass::presences_create()->value,
            PermissionsClass::presences_read()->value,
            PermissionsClass::presences_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
