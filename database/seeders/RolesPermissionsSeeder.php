<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\PermissionsClass;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class RolesPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    const SuperAdmin='Super administrateur';
    const Manager='Manager';
    public function run(): void
    {
        $permissions = PermissionsClass::toValues();

        foreach ($permissions as $key => $name) {
            Permission::firstOrCreate([
                'name' => $name,

            ]);
        }

        $role = Role::firstOrCreate([
            'name'=>self::SuperAdmin,
            'guard_name'=>'web',
        ]);

        $role->syncPermissions($permissions);

        $managerRole = Role::firstOrCreate([
            'name'=>self::Manager,
            'guard_name'=>'web',
        ]);
        

        $superAdmin=User::firstOrCreate([
            "email"=>"superadministrateur@laposte.tg",
            'password'=>Hash::make('11111111'),
            'name'=>'Super_administrateur',
            'service_id'=> 1,            
        ]);

        $superAdmin->syncRoles(self::SuperAdmin);

        
    }
}
