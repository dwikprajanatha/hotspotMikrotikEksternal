<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TbNikTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tb_nik')->delete();
        
        \DB::table('tb_nik')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nik' => '5103062107990002',
                'nama' => 'Dwi Prajanatha',
                'alamat' => 'Muding Mekar',
            ),
        ));
        
        
    }
}