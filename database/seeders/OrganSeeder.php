<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Organ;

class OrganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organs = [
            // Investigacion
            ['ACAV', 1], ['CENVIH', 1], ['CIDA', 1], ['CIEPE', 1],
            ['CODECYT', 1], ['CNTQ', 1], ['FIIIDT', 1], ['FONACIT', 1],
            ['INZIT', 1], ['IDEA', 1], ['IVIC', 1], ['ONCTI', 1],
            ['ANZOATEGUI', 1], ['BOLIVAR', 1], ['DELTA AMACURO', 1], ['MONAGAS', 1],
            ['NUEVA ESPARTA', 1], ['SUCRE', 1],

            // Telecomunicaciones
            ['ABAE', 2], ['CANTV', 2], ['CENDIT', 2], ['CENDITEL', 2],
            ['CNTI', 2], ['CONATI', 2], ['MOVILNET', 2], ['SUSCERTE', 2],
            ['TELECOM', 2], ['TGC', 2], ['ARAGUA', 2], ['CARABOBO', 2],
            ['COJEDES', 2], ['GUARICO', 2], ['MIRANDA', 2], ['YARACUY', 2]
        ];

        foreach ($organs as $organ) {
            Organ::create([
                'name'              => $organ[0],
                'institution_id'    => $organ[1]
            ]);
        }
    }
}
