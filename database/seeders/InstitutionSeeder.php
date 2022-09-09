<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\models\Institution;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Institution::create([
            'name'      => 'Mincy',
            'rif'       =>  '12345678',
        ]);
    }
}
