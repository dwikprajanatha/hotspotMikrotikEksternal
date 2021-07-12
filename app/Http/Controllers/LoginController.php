<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Session;

class LoginController extends Controller
{

    public function index(Request $request)
    {
        return view('hotspot/login', ['request' => $request->all()]);
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
        DB::transaction(function($conn) use(&$request, &$kategori){

            DB::connection('mysql')->transaction(function($conn) use(&$request, &$kategori){

                //buat akun di DB local
               $user_local = DB::connection('mysql')->table('tb_user_hotspot')->insert([
                   'nik_id' => 0, //sementara
                   'username' => $request->username,
                   // 'password' => $request->password,
                   'kategori' => $kategori,
                   'mac' => $request->mac,
                   'ip' => $request->ip,
               ]);

           });

           DB::connection('mysql_radius')->transaction(function($conn) use(&$request, &$kategori){
               $radcheck = DB::connection('mysql_radius')->table('radcheck')->insert([
                   'UserName' => $request->username,
                   'Attribute' => 'Cleartext-Password',
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
        
    }

}
