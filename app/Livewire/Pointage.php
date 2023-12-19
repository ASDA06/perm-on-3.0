<?php

namespace App\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Presence;
use Filament\Support\Colors\Color;
use Filament\Notifications\Notification;

class Pointage extends Component
{
    
    public function arrival()
    {

        $latestPresenceForThisUser = Presence::where('user_id', auth()->user()->id)->where('created_at', today())->first();

        if ($latestPresenceForThisUser) {
            if ($this->dailyCheck($latestPresenceForThisUser->created_at)) {

                Notification::make()
                    ->title('Attention')
                    ->body('Votre présence est déjà enrégistrée pour aujourd\'hui!')
                    ->icon('heroicon-o-shield-exclamation')
                    ->iconColor(Color::Amber)
                    ->duration(5000)
                    ->send();
            }
        } else {
            Presence::firstOrCreate([
                'date' => today(),
                'user_id' => auth()->user()->id,
                'heure_arrivee' => now(),
                'created_at' => today(),
                'updated_at' => now(),
            ]);

            Notification::make()
                ->title('Bienvenue')
                ->body('Votre arrivée a été enrégistrée!')
                ->icon('heroicon-o-user-plus')
                ->iconColor('success')
                ->duration(5000)
                ->send();
        }


    }

    public function departure()
    {
        $latestPresenceForThisUser = Presence::where('user_id', auth()->user()->id)->where('created_at', today())->first();

        if (Carbon::now() < config('app.heure_depart')) {
            Notification::make()
                ->title('Attention')
                ->body('Il n\'est pas encore ' . config('app.heure_depart')->format('H:i') . '. Vous ne pouvez pas partir!!!')
                ->icon('heroicon-o-shield-exclamation')
                ->iconColor('danger')
                ->duration(5000)
                ->send();
        } else {
            $latestPresenceForThisUser->update([
                "heure_depart" => now()
            ]);

            Notification::make()
                ->title('Bonne soirée')
                ->body('Votre départ a été enrégistré!')
                ->icon('heroicon-o-hand-raised')
                ->iconColor(Color::Cyan)
                ->duration(5000)
                ->send();
        }

    }

    private function dailyCheck($arrivalTime): bool
    {

        $arrival = Carbon::parse($arrivalTime)->addDay();

        return $arrival > Carbon::now();
    }
}
