<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use Filament\Actions;
use App\Enums\StatesClass;
use Filament\Actions\Action;
use App\Enums\PermissionsClass;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ServiceResource;

class EditService extends EditRecord
{
    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
            Action::make('Supprimer')
            ->color(Color::Red)
                ->action(function ($record) {
                    $record->update(['statut' => StatesClass::Inactive()->value]);
                    Notification::make()
                        ->success()
                        ->title('Suprimé(e)')
                        ->body('La permanence a été supprimée')
                        ->send();

                    return redirect(route('filament.admin.resources.permanences.index'));
                })
                ->requiresConfirmation(),
        ];
    }


    protected function authorizeAccess(): void
    {
        $user = auth()->user();
    
        $userPermission = $user->hasAnyPermission([
            // PermissionsClass::utilisateurs_create()->value,
            PermissionsClass::services_read()->value,
            PermissionsClass::services_update()->value,
            PermissionsClass::services_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
