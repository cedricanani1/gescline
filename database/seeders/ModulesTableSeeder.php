<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModulesTableSeeder extends Seeder
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
            array(
                'libelle' => 'default'
            ),
            array(
                'libelle' => 'administration'
            )
        );

        DB::table('modules')->insert($data);
    }
}
