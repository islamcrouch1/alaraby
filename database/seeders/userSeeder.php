<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'superAdmin',
            'email' => '',
            'password' => bcrypt('01006131794'),
            'phone' => '01006131794',
            // 'country_id' => '1',
            'gender' => 'male',
            'profile' => 'avatarmale.png',
            'phone_verified_at' => '2021-10-25 22:43:41',
        ]);

        $role = Role::where('name', 'superadministrator')->first();

        if (!$role) {
            $role = Role::create([
                'name' => 'superadministrator',
                'display_name' => 'superadministrator',
                'description' => 'superadministrator',
            ]);
        }

        $user->attachRole($role);

        $permissions = ['roles-create', 'roles-update', 'roles-read', 'roles-delete', 'roles-trash', 'roles-restore'];

        foreach ($permissions as $permission) {
            if (Permission::where('name', $permission)->first() == null) {
                Permission::create([
                    'name' => $permission,
                    'display_name' => $permission,
                    'description' => $permission
                ]);
            }
        }



        $role->attachPermissions($permissions);
    }
}
