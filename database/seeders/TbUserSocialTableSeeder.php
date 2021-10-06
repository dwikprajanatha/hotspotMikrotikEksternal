<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TbUserSocialTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tb_user_social')->delete();
        
        \DB::table('tb_user_social')->insert(array (
            0 => 
            array (
                'id' => 1,
                'social_id' => '1234567890',
                'nama' => 'Dwik Prajanatha Dauh',
                'username' => 'Dwik_Prajanatha',
                'email' => 'dwikprajanatha@gmail.com',
                'password' => '123qwdadasxasdwa',
                'platform' => 'facebook',
                'created_at' => '2021-10-04',
                'isDeleted' => '0',
            ),
        ));
        
        
    }
}