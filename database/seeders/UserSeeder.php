<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = array(
            'nom' => 'Admin',
            'prenoms' => 'Admin',
            'nationalite' => 'Ivoirien',
            'telephone' => '+225 0707000000',
            'date_naissance' => '2021-01-01',
            'adresse_domicile' => 'Abidjan, Adjame 220LGTS',
            'situation_matrimoniale' => 'mariee',
            'genre' => 'femme',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('1111'),
            'role' => 'admin',
            'statut'=> 'actif'
        );

        DB::table('users')->insert($data);

    }
}
