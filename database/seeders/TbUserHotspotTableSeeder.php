<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TbUserHotspotTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tb_user_hotspot')->delete();
        
        \DB::table('tb_user_hotspot')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nik_id' => 1,
                'username' => 'dwik',
                'kategori' => 'Dewasa',
                'mac' => '00:00:00:00:00',
                'ip' => '0.0.0.0',
                'created_at' => '2021-10-04',
                'isDeleted' => '0',
            ),
        ));
        
        
    }
}