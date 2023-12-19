@php
    use App\Filament\Resources\PermanenceResource\Pages\CreatePermanence;
    use App\Models\User;
    use App\Models\Service;
    use App\Models\permanenceUsers;
    use App\Models\Permanence;
    use App\Models\Departement;
    use App\Functions\DateFunction;
    use carbon\carbon;

    $lastKValue = null;

    $loggedUser = auth()->user();

    $loggedService = Service::where('id', $loggedUser->service_id)->first();

    $loggedDepartement = Departement::where('id', $loggedService->departement_id)->first();

    $record = Permanence::where('departement_id', $loggedDepartement->id)
        ->orderBy('created_at', 'desc')
        ->first();

    if ($record) {
        //get related records in pivot table
        $relatedData = PermanenceUsers::where('permanence_id', $record->id)->get();

        $services = Service::where('departement_id', auth()->user()->service->departement_id)
            ->select('nom_service')
            ->get();

        $departement = Departement::where('id', $record->departement_id)
            ->get()
            ->value('nom_departement');

        $firstPermanenceDay = Carbon::parse($relatedData->first()->date)->TranslatedFormat('l, d M Y');

        $lastPermanenceDay = Carbon::parse($relatedData->last()->date)->TranslatedFormat('l, d M Y');

        $months = [];
        $days = [];
        $users = [];

        //putting days in an array
        foreach ($relatedData as $key => $data) {
            if (!in_array($data->date, $days)) {
                $days[] = $data->date;
            }
        }

        //putting months in an array
        foreach ($days as $key => $day) {
            if (!in_array(carbon::parse($day)->TranslatedFormat('F'), $months)) {
                $months[] = carbon::parse($day)->TranslatedFormat('F');
            }
        }

        $users = [];
        $userNames = [];
        $intermediateArray = [];
        $emptyArray = [];

        //putting  users in array
        foreach ($relatedData as $key => $userField) {
            // for ($i = 0; $i < $services->count(); $i++) {
            //     if (User::find($userField->user_id[$i]) !== null) {
            //         $users[] = User::find($userField->user_id[$i]);
            //     }
            // }

            for ($i = 0; $i < $services->count(); $i++) {
            if (User::find($userField->user_id[$i]['users']) !== null) {
                $users[] = User::find($userField->user_id[$i]['users']);
            }      
        }

            $usersCollection = collect($users)->sortBy('service_id');

            foreach ($usersCollection as $aCollection) {
                array_push($intermediateArray, $aCollection);
            }
            $usersCollection = collect($emptyArray);
            $users = [];
        }

        foreach ($intermediateArray as $key => $user) {
            if ($user != null) {
                $userNames[] = $user->name;
            }
        }

        $y = 0;
        $z = 0;
    }

@endphp

@if ($record)
    <x-filament-widgets::widget>
        <x-filament::section>
            <!-- component -->
            <h2 class="text-4xl  text-center py-6 font-extrabold dark:text-white">Planning des permanences pour les dates
                du
                {{ $firstPermanenceDay }} au {{ $lastPermanenceDay }}</h2>
            <h2 class="text-4xl  text-center py-2 font-extrabold dark:text-white">SPT / {{ $departement }}</h2>
            <div class="overflow-hidden rounded-lg border border-gray-200 shadow-md m-5">
                <table class="w-full border-collapse bg-white text-left text-sm text-gray-500">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-4 font-medium text-gray-900">Dates</th>
                            @foreach ($services as $service)
                                <th scope="col" class="px-6 py-4 font-medium text-gray-900">{{ $service->nom_service }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 border-t border-gray-100">
                        @if ($days)
                            @foreach ($months as $month)
                                <tr class="hover:bg-teal-50">
                                    <th class=" px-6 py-4 font-normal text-gray-900 text-center">
                                        {{ $month }}
                                    </th>
                                </tr>
                                @foreach ($days as $day)
                                    @if (carbon::parse($day)->translatedFormat('F') == $month)
                                        <tr class="hover:bg-gray-50">
                                            <th class="flex gap-3 px-6 py-4 font-normal text-gray-900">
                                                <div class="relative h-10 w-10">
                                                </div>
                                                <div class="text-sm">
                                                    <div class="font-medium text-gray-700">
                                                        {{ carbon::parse($day)->translatedFormat('l, d F Y') }}</div>
                                                    {{-- <div class="text-gray-400">jobs@sailboatui.com</div> --}}
                                                </div>
                                            </th>
                                            @for ($k = 0; $k < $services->count(); $k++)
                                                <td class="px-6 py-4">
                                                    <span class="h-1.5 w-1.5 rounded-full">
                                                        {{ $userNames[$y + $k] }}
                                                    </span>
                                                </td>
                                            @endfor

                                            @php
                                                $y = $y + $k;
                                            @endphp
                                        @elseif($loop->last)
                                        @break

                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    @endif

                </tbody>
            </table>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
@else
<x-filament-widgets::widget>
    <x-filament::section>
        Aucun calendrier de permanences à afficher
    </x-filament::section>
</x-filament-widgets::widget>
@endif
