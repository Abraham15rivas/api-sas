<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            (object) [
                'name'          => 'admin',
                'email'         => 'admin@test.com',
                'phone'         => '123456789',
                'dni'           => '123456789',
                'password'      => Hash::make('secret123'),
                'role_id'       => 1,
                'institution_id'=> 1
            ],
            (object) [
                'name'          => 'client',
                'email'         => 'client@test.com',
                'phone'         => '123456789',
                'dni'           => '125454',
                'password'      => Hash::make('secret123'),
                'role_id'       => 2,
                'institution_id'=> 1
            ]
        ];

        foreach ($users as $user) {
            User::create([
                'name'          => $user->name,
                'email'         => $user->email,
                'phone'         => $user->phone,
                'dni'           => $user->dni,
                'password'      => $user->password,
                'role_id'       => $user->role_id,
                'institution_id'=> $user->institution_id
            ]);
        }
    }
}
