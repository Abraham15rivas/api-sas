<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = ['Admin', 'Client'];

        foreach ($roles as $role) {
            Role::create([
                'name' => $role
            ]);
        }
    }
}
