<?php

namespace App\Filament\Resources\PresenceResource\Pages;

use Filament\Actions;
use App\Enums\StatesClass;
use App\Enums\PermissionsClass;
use Filament\Pages\Actions\Action;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\PresenceResource;

class EditPresence extends EditRecord
{
    protected static string $resource = PresenceResource::class;

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
            PermissionsClass::presences_read()->value,
            PermissionsClass::presences_update()->value,
            PermissionsClass::presences_delete()->value,

        ]);
    
        abort_if(! $userPermission, 403, __("Vous n'avez pas access à cette page"));
    }
}
