<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $names=[
            'Serge',
            'Greg',
            'William',
            'Dorcas',
            'Joanita',
            'Rosalie',
            'Hermes',
            'Hercules',
            'Félicité'
         ];
//========================ADMIN=====================================
        // $superAdmin=User::firstOrCreate([
        //     "email"=>"superadministrateur@laposte.tg",
        //     'password'=>Hash::make('11111111'),
        //     'name'=>'Super_administrateur',
        //     'service_id'=> 1,
        // ]);
//========================otherServices=============================
        foreach($names as $key => $name)
        {   
            User::firstOrCreate([
                "email" => $name."@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => $name,
                'service_id' =>  mt_rand(4,5),
            ]);
        }
//========================exploitation=============================
        User::firstOrCreate([
            "email" => "Jeremie@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Jeremie',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Boris@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Boris',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Ola@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Ola',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Looky@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Looky',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Barthélémie@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Barthélémie',
                'service_id' =>  1,
        ]);

        User::firstOrCreate([
            "email" => "Agbessi@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Agbessi',
                'service_id' =>  1,
        ]);
//========================Etudes=============================

        User::firstOrCreate([
            "email" => "Daniel@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Daniel',
                'service_id' =>  3,
        ]);

        User::firstOrCreate([
            "email" => "Adjo@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Adjo',
                'service_id' =>  3,
        ]);

        User::firstOrCreate([
            "email" => "Abraham@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Abraham',
                'service_id' =>  3,
        ]);

        User::firstOrCreate([
            "email" => "Yamine@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Yamine',
                'service_id' =>  3,
        ]);

        User::firstOrCreate([
            "email" => "Kevin@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Kevin',
                'service_id' =>  3,
        ]);
        
//========================Reseaux=============================

        User::firstOrCreate([
            "email" => "Eli@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Eli',
                'service_id' =>  2,
        ]);

        User::firstOrCreate([
            "email" => "Sylvanus@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Sylvanus',
                'service_id' =>  2,
        ]);

        User::firstOrCreate([
            "email" => "Euloge@laposte.tg",
                'password' => Hash::make('11111111'),
                'name' => 'Euloge',
                'service_id' =>  2,
        ]);
    }
}
