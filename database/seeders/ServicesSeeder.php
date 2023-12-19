<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Enums\ServicesClass;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = ServicesClass::toValues();

        foreach ($services as $key => $service) 
        {
            Service::firstOrCreate([
                'nom_service'=> $service,
                'departement_id'=> 1,
            ]);
        }

        Service::firstOrCreate([
            'nom_service'=> 'Recouvrement',
            'departement_id'=> 2,
        ]);

        Service::firstOrCreate([
            'nom_service'=> 'Crédit',
            'departement_id'=> 2,
        ]);
    }
}
