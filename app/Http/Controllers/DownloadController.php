<?php

namespace App\Http\Controllers;

use carbon\carbon;
use App\Models\User;
use App\Models\Service;
use App\Models\Permanence;
use App\Models\Departement;
use Illuminate\Http\Request;
use App\Models\permanenceUsers;
use App\Functions\DateFunction;
use LaravelDaily\Invoices\Invoice;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Classes\InvoiceItem;

class DownloadController extends Controller
{
    public function downloadPdf(Permanence $record)
    {
        // $lastKValue = null;

        // // returns every order column
        // $participantsOrder = $record
        //     ->select('order')
        //     ->where('id', $record->id)
        //     ->pluck('order');

        // foreach ($participantsOrder as $key => $agentsTrioPerPermanenceDay) {
        //     $permanenceDayWithAgents = json_decode($agentsTrioPerPermanenceDay, true);

        //     for ($i = $key; $i < count($permanenceDayWithAgents); $i++) {
        //         for ($j = 0; $j < count($permanenceDayWithAgents[$i]['participants']); $j++) {
        //             $participantsIds[] = $permanenceDayWithAgents[$i]['participants'][$j];
        //         } // retrieving  Ids of participants from the orders Json Column and putting them in an array
        //     }
        // }

        // $usersArray = [];

        // $lesServices = Service::where('departement_id', auth()->user()->service->departement_id)
        //     ->select('nom_service')
        //     ->get();

        // $nomDepartement = Departement::where('id', $record->departement_id)->get()->value('nom_departement');

        // $dates = DateFunction::getDateForSpecificDayBetweenDates($record->date_debut, $record->date_fin, env('PERMANENCE'));

        // $annee = carbon::parse($record->date_debut)->format('Y');

        // $months = [];
        // $days = [];
        // $usersNames = [];
        // $y = 0;
        // $z = 0;

        // foreach ($dates as $key => $date) {
        //     //putting months in an array
        //     if (!in_array(carbon::parse($date)->format('F'), $months)) {
        //         $months[] = carbon::parse($date)->format('F');
        //     }
        //     //putting days in an array
        //     if (!in_array(carbon::parse($date)->format('l, d-m-Y'), $days)) {
        //         $days[] = carbon::parse($date)->format('l, d-m-Y');
        //     }
        // }

        // foreach ($participantsIds as $key => $id) {
        //     $usersNames[] = User::where('id', $id)
        //         ->select('name', 'id')
        //         ->get()
        //         ->value('name');
        // }

        //============================================


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

    // dd($days[0]->translatedFormat('l, d F Y'));

    $y = 0;
    $z = 0;

        // ============================================================
        //DEPARTEMENTS
        $data = new Party([
            'nom_departements' => $departement,
            'services'  => $services,
            // 'dates' =>  $days,
            'months' =>  $months,
            'days' => $days,
            // 'annee' => $annee,
            'userNames' => $userNames,
            'currentRecord' => $record

            // 'custom_fields' => [
            //     'note'        => 'IDDQD',
            //     'business id' => '365#GG',
            // ],
        ]);

        // $services= new Party($lesServices);


        //SERVICES
        $customer = new Party([

            'name' => 'Ashley Medina',
            'address' => 'The Green Street 12',
            'code' => '#22663214',
            'custom_fields' => [
                'order number' => '> 654321 <',
            ],
        ]);


        $servicesItems = [];

        foreach ($services as $service) {
            $servicesItems[] = (new InvoiceItem())->title($service->nom_service);
        }

        $items = [
            (new InvoiceItem())
                ->title('Service 1')
                ->description('Your product or service description')
                ->pricePerUnit(47.79)
                ->quantity(2)
                ->discount(10),
            (new InvoiceItem())->title('Service 2')->pricePerUnit(71.96)->quantity(2),
            // (new InvoiceItem())->title('Service 3')->pricePerUnit(4.56),
            // (new InvoiceItem())->title('Service 4')->pricePerUnit(87.51)->quantity(7)->discount(4)->units('kg'),
            // (new InvoiceItem())->title('Service 5')->pricePerUnit(71.09)->quantity(7)->discountByPercent(9),
            // (new InvoiceItem())->title('Service 6')->pricePerUnit(76.32)->quantity(9),
            // (new InvoiceItem())->title('Service 7')->pricePerUnit(58.18)->quantity(3)->discount(3),
            // (new InvoiceItem())->title('Service 8')->pricePerUnit(42.99)->quantity(4)->discountByPercent(3),
            // (new InvoiceItem())->title('Service 9')->pricePerUnit(33.24)->quantity(6)->units('m2'),
            // (new InvoiceItem())->title('Service 11')->pricePerUnit(97.45)->quantity(2),
            // (new InvoiceItem())->title('Service 12')->pricePerUnit(92.82),
            // (new InvoiceItem())->title('Service 13')->pricePerUnit(12.98),
            // (new InvoiceItem())->title('Service 14')->pricePerUnit(160)->units('hours'),
            // (new InvoiceItem())->title('Service 15')->pricePerUnit(62.21)->discountByPercent(5),
            // (new InvoiceItem())->title('Service 16')->pricePerUnit(2.80),
            // (new InvoiceItem())->title('Service 17')->pricePerUnit(56.21),
            // (new InvoiceItem())->title('Service 18')->pricePerUnit(66.81)->discountByPercent(8),
            // (new InvoiceItem())->title('Service 19')->pricePerUnit(76.37),
            // (new InvoiceItem())->title('Service 20')->pricePerUnit(55.80),
        ];

        $notes = [
            'Chef',
            $departement,
        ];
        $notes = implode("<br>", $notes);



        $invoice = Invoice::make('receipt')
            ->series('BIG')
            // ability to include translated invoice status
            // in case it was paid
            // ->status(__('invoices::invoice.paid'))
            // ->sequence(667)
            // ->serialNumberFormat('{SEQUENCE}/{SERIES}')
            ->seller($data)
            ->buyer($customer)
            // ->seller($a)
            ->date(now())
            ->dateFormat('d/m/Y')
            // ->payUntilDays(14)
            // ->currencySymbol('$')
            // ->currencyCode('USD')
            // ->currencyFormat('{SYMBOL}{VALUE}')
            // ->currencyThousandsSeparator('.')
            // ->currencyDecimalPoint(',')
            ->filename($data->nom_departements)
            // ->setCustomData($servicesItems)
            ->addItems($items)
            ->notes($notes)
            ->logo(public_path('logo.png'))
            // ->logo(public_path('vendor/invoices/sample-logo.png'))
            // You can additionally save generated invoice to configured disk
            ->save('public');

        $link = $invoice->url();
        // Then send email to party with link

        // And return invoice itself to browser or have a different view
        return $invoice->stream();
    }
}
