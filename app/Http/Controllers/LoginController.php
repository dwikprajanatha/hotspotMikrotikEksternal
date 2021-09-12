<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Session;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{

    public function index(Request $request)
    {
        return view('hotspot/login', ['request' => $request->all()]);
    }

    public function privacy(Request $request)
    {
        return view('hotspot.privacy');
    }


    public function create(Request $request)
    {
        return view('hotspot/register',['request' => $request]);
    }

    public function daftar(Request $request)
    {
        
        //verifikasi nik
        $cek = DB::connection('mysql')->table('tb_nik')->select('id')->where('nik',$request->nik)->first();
        
        if($cek == null){
            Session::flash('error', 'NIK Tidak Terdaftar!');
            return redirect()->back();
        }

        //check if exists
        $cek_ada = DB::connection('mysql')->table('tb_user_hotspot')->where('nik_id',$request->nik)->first();

        if($cek_ada != null){
            Session::flash('error', 'NIK Sudah terdaftar!');
            return redirect()->back();
        }
        
        //pisah NIK
        $nik_pisah = str_split($request->nik);

        
        $tgl_lahir = implode('',[$nik_pisah[6],$nik_pisah[7]]);
        $bulan_lahir = implode('',[$nik_pisah[8],$nik_pisah[9]]);
        $tahun_lahir = implode('',[$nik_pisah[10],$nik_pisah[11]]);
        
        if($tgl_lahir > 31){
            $tgl_lahir = $tgl_lahir - 40;
        }

        //cek umur
        $bday = DateTime::createFromFormat('d-m-y',join('-',[$tgl_lahir,$bulan_lahir,$tahun_lahir]));
        $now = new DateTime(date('d-m-Y'));
        $age = $now->diff($bday)->y;


        //klasifikasi umur
        if($age >= 21){
            $kategori = "Dewasa";

        } else if($age > 14 && $age < 21){
            $kategori = "Remaja";
            
        } else{
            $kategori = "Anak";
        }

        //start DB transaction
        DB::transaction(function() use(&$request, &$kategori, &$cek){

            // dd($request,$kategori,$cek);

            DB::connection('mysql')->transaction(function() use(&$request, &$kategori, &$cek){

                //buat akun di DB local
               $user_local = DB::connection('mysql')->table('tb_user_hotspot')->insert([
                   'nik_id' => $cek->id, //sementara
                   'username' => $request->username,
                   // 'password' => $request->password,
                   'kategori' => $kategori,
                   'mac' => $request->mac,
                   'ip' => $request->ip,
               ]);

           });

           DB::connection('mysql_radius')->transaction(function() use(&$request, &$kategori){
               $radcheck = DB::connection('mysql_radius')->table('radcheck')->insert([
                   'username' => $request->username,
                   'attribute' => 'Cleartext-Password',
                   'op' => ':=',
                   'value' => $request->password,
               ]);

               $radusergroup = DB::connection('mysql_radius')->table('radusergroup')->insert([
                   'username' => $request->username,
                   'groupname' => $kategori,
                   'priority' => 10,
               ]);

           });

        });

        return view('hotspot/loginAfterRegister',['request' => $request, 'username' => $request->username, 'password' => $request->password]);
        // $request->session()->put('userSuccess', 'Akun Berhasil dibuat!, Silahkan Login');
        // return redirect()->intended('http://10.0.0.1/');
        
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {

            //get the request
            $request = Session::get('request');
            
            $user = Socialite::driver($provider)->stateless()->user();

            $username = preg_replace('/\s+/', '_', $user->name);

            $password = time();
            
            // dd($user);

            /// lakukan pengecekan apakah facebook/google id nya sudah ada apa belum
            $user_db = DB::connection('mysql')->table('tb_user_social')->where('social_id', $user->id)->first();
            
            if($user_db == null){

                $cek_username = DB::connection('mysql')->table('tb_user_social')->where('username', $username)->first();
                
                if($cek_username != null){
                    $username = $username . "_" . $provider;
                }

                DB::transaction(function() use(&$provider, &$user, &$password, &$username) {

                    DB::connection('mysql')->table('tb_user_social')->insert([
                        'social_id' => $user->id,
                        'nama' => $user->name,
                        'username' => $username,
                        'email' => $user->email,
                        'password' => $password,
                        'platform' => $provider,
                        'created_at' => date('Y-m-d'),
                    ]);

                    DB::connection('mysql_radius')->table('radcheck')->insert([
                        'username' => $username ."_".$provider,
                        'attribute' => 'Cleartext-Password',
                        'op' => ':=',
                        'value' => $password,
                    ]);

                    $radusergroup = DB::connection('mysql_radius')->table('radusergroup')->insert([
                        'username' => $request->username,
                        'groupname' => "social_media",
                        'priority' => 10,
                    ]);

                });

                return view('hotspot/loginAfterRegister',['request' => $request, 'username' => $username, 'password' => $password]);
 
            } else {
                
                return view('hotspot/loginAfterRegister',['request' => $request, 'username' => $user_db->username, 'password' => $user_db->password]);
            }


        } catch (\Exception $e) {
            // echo("<h1> 500 Internal Server Error </h1>");
            dd($e->getMessage());
        }
    }

}
