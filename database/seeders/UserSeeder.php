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
            ['admin', 'admin@test.com', '04241313435', 'secret123', 1, 1, 7],

            // Investigacion
            ['Arelis Orama', 'acavpresidencia2019@gmail.com', '04123882903', '3Q7RX!6KSfn2', 2, 1, 1],
            ['José Chitty', 'josechitty@gmail.com', '04141523809', '$x2gK0nT2TDu', 2, 1, 2],
            ['Pedro Grima', 'peg1952@gmail.com', '04264645208', '%gmS85bj7n1U', 2, 1, 3],
            ['Victor Gamarra', 'victorjosegamarram@gmail.com', '04265539579', 'I8#f54#i1t1E', 2, 1, 4],
            ['Juan Blanco', 'juablanco@gmail.com', '04143336362', 'X2^@5SEi4Jz#', 2, 1, 5],
            ['Magaly Henriquez', 'mhenriquez.cntq@gmail.com', '04166088590', 'hSzXAS*wM427', 2, 1, 6],
            ['Francisco Duran ', 'frandur@gmail.com', '04165170015', 'fdB%9aA6jML5', 2, 1, 7],
            ['Francy Rodríguez', 'rodrigueztfrancy@gmail.com', '04241614681', 'XR59M%s6#Xyw', 2, 1, 8],
            ['Mayuli Urdaneta', 'mayuliurdaneta@gmail.com', '04166622150', '7!2T6$Bx%83n', 2, 1, 9],
            ['Gloria Carvalho', 'carvalhokassar@gmail.com', '04265336513', 'erP50G*GY10m', 2, 1, 10],
            ['Alberto Quintero', 'albertojosequinteroaraque@gmail.com', '04166188252', 'H#5E063iTAxr', 2, 1, 11],
            ['Roberto Betancourt', 'roberto.a.betancourt@gmail.com', '04125896839', 'Hywd8EQ$v28W', 2, 1, 12],
            ['VICTOR HUGO', 'ccb2s3a@gmail.com', '04161929769', '$97%7sB2Zac4', 2, 1, 13],
            ['LUIS CARDENAS', 'pozoverde70@gmail.com', '04165862328', 'o6T5FP4@n%^U', 2, 1, 14],
            ['JHONY GOMEZ', 'presidenciafundacitedelta@gmail.com', '04149978415', 'uK1vGgN00P7*', 2, 1, 15],
            ['MARIA HERNANDEZ', 'ariamandrea26@gmail.com', '04249145961', '3K9c6ecHU#Ul', 2, 1, 16],
            ['VANESA MALDONADO', 'presidenciafundacitene@gmail.com', '04248738025', '8BsA4rR80L^L', 2, 1, 17],
            ['ENRIQUE ORTIZ', 'sociologo.dygt@gmail.com', '04265962981', 'wXT%TY699q32', 2, 1, 18],

            // Telecomunicaciones
            ['Adolfo Godoy', 'godoypernia@gmail.com', '04146425658', '&k05jT28qpV!', 2, 2, 19],
            ['Jesus Aldana', 'jesusaldana@gmail.com', '04165433604', 'VkF0eD60M0*n', 2, 2, 20],
            ['Dino DI Rosa', 'dino.dirosa@gmail.com', '04265165018', '2ss!3&rD8ymS', 2, 2, 21],
            ['Oscar González', 'oscargueller@gmail.com', '04265364624', '@pt7Be&8W72N', 2, 2, 22],
            ['Carlos Parra', 'ceparra@gmail.com', '04166080675', 's*47G5r3w!!N', 2, 2, 23],
            ['Georlexandra Díaz', 'gd.conati@gmail.com', '04166102626', '14992y@K6mrv', 2, 2, 24],
            ['Anibal Briceño', 'anibal.briceno@gmail.com', '04142997080', '3nV#8JIC9jm5', 2, 2, 25],
            ['Carlos Parra', 'ceparra2@gmail.com', '04166080675', 'gvcWPrBO^294', 2, 2, 26],
            ['Antonio Díaz', 'cleandi64@gmail.com', '04166449210', '3xL4ni6$I9@Q', 2, 2, 27],
            ['Javier Padron', 'javierpadron88@gmail.com', '04166241652', '@5uoLeV136#Z', 2, 2, 28],
            ['PEDRO MERENTES', 'pedromerentes@gmail.com', '04265310939', 'kiIa2M020!i&', 2, 2, 29],
            ['EFRAIN MARIN', 'efrain77@gmail.com', '04244260272', '%zbM5a!0IAy1', 2, 2, 30],
            ['PEDRO MERENTES', 'mincytcojedes@gmail.com', '04144027710', '2RT!702fE$BX', 2, 2, 31],
            ['JIM MADRID', 'jjmadridh@gmail.com', '04169495145', 'B#OIT9vj@1l1', 2, 2, 32],
            ['HECTOR CONSTANT', 'hector.constant@gmail.com', '04142445225', 'QeHt!&2*5J3K', 2, 2, 33],
            ['MIGUEL SORZANO', 'miguelangel1989.33@gmail.com', '04125264050', 'D4*9YyXc*n8b', 2, 2, 34]
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

        // Evaluar posibilidad de colocar usuarios con mas de un organo, es decir, 
        // relacion muchos a muchos entre el modelo user <-> organ... proximo update
        // Tambien agregar la posibilidad de almacenar mas de un telefono, ya que la mayoria de los
        // usuarios posen hasta tres telefonos
    }
}
