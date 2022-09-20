<?php

namespace Database\Seeders;

use App\Http\Middleware\Admin;
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
            // Admin
            ['admin', 'admin@agenda.com', '04241313435', 'secret123', 1, 1, 7],

            // Investigacion
            ['ACAV', 'acav@agenda.com', '04123882903', 'acav*123', 2, 1, 1],
            ['CENVIH', 'cenvih@agenda.com', '04141523809', 'cenvih*123', 2, 1, 2],
            ['CIDA', 'cida@agenda.com', '04264645208', 'cida*123', 2, 1, 3],
            ['CIEPE', 'ciepe@agenda.com', '04265539579', 'ciepe*123', 2, 1, 4],
            ['CODECYT', 'codecyt@agenda.com', '04143336362', 'codecyt*123', 2, 1, 5],
            ['CNTQ', 'cntq@agenda.com', '04166088590', 'cntq*123', 2, 1, 6],
            ['FIIIDT', 'fiiidt@agenda.com', '04165170015', 'fiiidt*123', 2, 1, 7],
            ['FONACIT', 'fonacit@agenda.com', '04241614681', 'fonacit*123', 2, 1, 8],
            ['INZIT', 'inzit@agenda.com', '04166622150', 'inzit*123', 2, 1, 9],
            ['IDEA', 'idea@agenda.com', '04265336513', 'idea*123', 2, 1, 10],
            ['IVIC', 'ivic@agenda.com', '04166188252', 'ivic*123', 2, 1, 11],
            ['ONCTI', 'oncti@agenda.com', '04125896839', 'oncti*123', 2, 1, 12],
            ['ANZOATEGUI', 'anzoategui@agenda.com', '04161929769', 'anzoategui*123', 2, 1, 13],
            ['BOLIVAR', 'bolivar@agenda.com', '04165862328', 'bolivar*123', 2, 1, 14],
            ['DELTA AMACURO', 'deltamacuro@agenda.com', '04149978415', 'deltamacuro*123', 2, 1, 15],
            ['MONAGAS', 'monagas@agenda.com', '04249145961', 'monagas*123', 2, 1, 16],
            ['NUEVA ESPARTA', 'nuevaesparta@agenda.com', '04248738025', 'nuevaesparta*123', 2, 1, 17],
            ['SUCRE', 'sucre@agenda.com', '04265962981', 'sucre*123', 2, 1, 18],

            // Telecomunicaciones
            ['ABAE', 'abae@agenda.com', '04146425658', 'abae*123', 2, 2, 19],
            ['CANTV', 'cantv@agenda.com', '04165433604', 'cantv*123', 2, 2, 20],
            ['CENDIT', 'cendit@agenda.com', '04265165018', 'cendit*123', 2, 2, 21],
            ['CENDITEL', 'cenditel@agenda.com', '04265364624', 'cenditel*123', 2, 2, 22],
            ['CNTI', 'cnti@agenda.com', '04166080675', 'cnti*123', 2, 2, 23],
            ['CONATI', 'conati@agenda.com', '04166102626', 'conati*123', 2, 2, 24],
            ['MOVILNET', 'movilnet@agenda.com', '04142997080', 'movilnet*123', 2, 2, 25],
            ['SUSCERTE', 'suscerte@agenda.com', '04166080675', 'suscerte*123', 2, 2, 26],
            ['TELECOM', 'telecom@agenda.com', '04166449210', 'telecom*123', 2, 2, 27],
            ['TGC', 'tgc@agenda.com', '04166241652', 'tgc*123', 2, 2, 28],
            ['ARAGUA', 'aragua@agenda.com', '04265310939', 'aragua*123', 2, 2, 29],
            ['CARABOBO', 'carabobo@agenda.com', '04244260272', 'carabobo*123', 2, 2, 30],
            ['COJEDES', 'cojedes@agenda.com', '04144027710', 'cojedes*123', 2, 2, 31],
            ['GUARICO', 'guarico@agenda.com', '04169495145', 'guarico*123', 2, 2, 32],
            ['MIRANDA', 'miranda@agenda.com', '04142445225', 'miranda*123', 2, 2, 33],
            ['YARACUY', 'yaracuy@agenda.com', '04125264050', 'yaracuy*123', 2, 2, 34]
        ];

        foreach ($users as $user) {
            User::create([
                'name'          => trim($user[0]),
                'email'         => trim($user[1]),
                'phone'         => trim($user[2]),
                'password'      => trim(Hash::make($user[3])),
                'role_id'       => $user[4],
                'institution_id'=> $user[5],
                'organ_id'      => $user[6]
            ]);
        }
    }
}
