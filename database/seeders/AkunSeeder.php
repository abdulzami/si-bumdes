<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'username' => 'superadmin',
                'name' => 'Super Admin',
                'level' => 'super-admin',
                'password' => bcrypt('superadmin12345678'),
                'nama_kepala_usaha' => 'Muhammad Ibrahim'
            ],
            [
                'username' => 'ziyadgalon',
                'name' => 'Galon Mukti Bina Arta',
                'level' => 'admin',
                'password' => bcrypt('ziyadgalon'),
                'wujud_usaha' => 'Produk',
                'nama_kepala_usaha' => 'Muhammad Ziyad Asyrof'
            ],
            [
                'username' => 'asad1234',
                'parent_id' => 2,
                'name' => 'Cak Asad',
                'level' => 'kasir',
                'password' => bcrypt('asad1234'),
            ],
            // ,
            // [
            //     'username' => 'kasirprint',
            //     'name' => 'Kasir Usaha Print',
            //     'level' => 'kasir',
            //     'password' => bcrypt('kasirprint123'),
            //     'wujud_usaha' => 'produk'
            // ],
            // [
            //     'username' => 'kasirgalon',
            //     'name' => 'Kasir Usaha Isi Ulang Galon',
            //     'level' => 'kasir',
            //     'password' => bcrypt('kasirgalon123'),
            //     'wujud_usaha' => 'produk'
            // ],
        ];
        foreach ($user as $key => $value){
            User::create($value);
        }
    }
}
