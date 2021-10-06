<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nip' => '199912121220',
                'nama' => 'Made Made',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => '$2y$10$uQHrq8UZoPSWrbZ0IDVAKuJacxb/cjJJb3OEnhoPokbURPukmqh3C',
                'role' => '1',
                'api_token' => 'qi6almoMlXenjUCfDLlOF0RN75yc1WlRakcWyo7lD0xvPSYFDfmfECwnqnT9',
                'isDeleted' => '0',
            ),
        ));
        
        
    }
}