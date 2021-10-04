<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Str;
use Hash;
use DB;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::connection('mysql')->table('users')->insert([
            'nip' => '199912121220',
            'nama' => 'Made Made',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin1234'),
            'role' => 1,
            'api_token' => Str::random(10),
        ]);
    }
}
