<?php

namespace Database\Seeders;

use App\Models\Departement;
use Illuminate\Database\Seeder;
use App\Enums\DepartementsClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departements = DepartementsClass::toValues();

        foreach ($departements as $key => $departement) 
        {
            Departement::firstOrCreate([
                'nom_departement'=> $departement,
            ]);
        }
    }
}
