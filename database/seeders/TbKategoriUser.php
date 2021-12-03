<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TbKategoriUser extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('tb_kategori_user')->delete();
        
        \DB::table('tb_kategori_user')->insert(array (
            0 => 
            array (
                'id' => 1,
                'group' => 'masyarakat_umum',
                'rx-rate' => '8M',
                'tx_rate' => '8M',
                'min_rx_rate' => '4M',
                'min_tx_rate' => '4M',
                'priority' => '4',
                'idle_timeout' => '0',
                'session_timeout' => '0',
                'port_limit' => '1',
                'status' => '1',
            ),
            1 =>
            array (
                'id' => 1,
                'group' => 'social_media',
                'rx-rate' => '4M',
                'tx_rate' => '4M',
                'min_rx_rate' => '1M',
                'min_tx_rate' => '1M',
                'priority' => '8',
                'idle_timeout' => '0',
                'session_timeout' => '3600',
                'port_limit' => '1',
                'status' => '1',
            ),
        ));
        
        
    }
}