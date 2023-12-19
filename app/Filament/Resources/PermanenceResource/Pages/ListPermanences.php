<?php

namespace App\Filament\Resources\PermanenceResource\Pages;

use Filament\Actions;
use App\Models\Service;
use App\Enums\StatesClass;
use App\Enums\PermissionsClass;
use Illuminate\Support\Facades\DB;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PermanenceResource;

class ListPermanences extends ListRecords
{
    protected static string $resource = PermanenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

        ];
    }


    protected function getTableQuery(): ?Builder
    {
        $loggedService = Service::where('id', auth()->user()->service_id)
    ->value('departement_id');

return static::getResource()::getEloquentQuery()
    ->whereHas('departement', function ($query) use ($loggedService) {
        $query->where('departement_id', $loggedService);
    })
    ->join('departements', 'permanences.departement_id', 'departements.id')
    ->join('permanence_user', 'permanence_user.permanence_id', 'permanences.id')
    ->where('permanences.statut', StatesClass::Active()->value)
    ->select('permanences.id', 'permanences.created_at',  'nom_departement as departement', DB::raw('MAX(date) as date_fin'), DB::raw('MIN(date) as date_debut'))
    ->groupBy('permanences.id', 'permanences.created_at', 'nom_departement') // Ajouter les colonnes non agrégées dans GROUP BY
    ;


    }

    protected function authorizeAccess(): void
    {
        $user = auth()->user();

        $userPermission = $user->hasAnyPermission([
            PermissionsClass::permanences_create()->value,
            PermissionsClass::permanences_read()->value,
            PermissionsClass::permanences_update()->value,
            // PermissionsClass::utilisateurs_delete()->value,

        ]);

        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
