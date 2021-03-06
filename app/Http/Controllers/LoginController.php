<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Session;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LoginController extends Controller
{

    public function index(Request $request)
    {
        // //get Pengumuman
        $arr_pengumuman = [];

        $pengumuman = DB::connection('mysql')->table('tb_pengumuman')
                        ->where('status', 1)
                        ->get();

        foreach($pengumuman as $p){

            $files = DB::connection('mysql')->table('tb_det_pengumuman')
                        ->where('id_pengumuman', $p->id)
                        ->where('status', 1)
                        ->get();

            array_push($arr_pengumuman, [
                'title' => $p->title, 
                'desc' => $p->desc, 
                'created_at' => $p->created_at,
                'images' => $files,
            ]);
        }

        return view('hotspot.login', ['request' => $request->all(), 'pengumuman' => $arr_pengumuman]);
    }

    public function loginTest(Request $request)
    {
        //get Pengumuman
        $arr_pengumuman = [];

        $pengumuman = DB::connection('mysql')->table('tb_pengumuman')
                        ->where('status', 1)
                        ->get();

        foreach($pengumuman as $p){

            $files = DB::connection('mysql')->table('tb_det_pengumuman')
                        ->where('id_pengumuman', $p->id)
                        ->where('status', 1)
                        ->get();

            array_push($arr_pengumuman, [
                'title' => $p->title, 
                'desc' => $p->desc, 
                'created_at' => $p->created_at,
                'images' => $files,
            ]);
        }

        // dd((object)$arr_pengumuman);

        return view('hotspot.logintest', ['request' => $request->all(), 'pengumuman' => $arr_pengumuman]);
    }

    public function redirectStatus()
    {
        $url = "http://mikrotik.lpk-resortkuta.com/status";

        return redirect($url);
    }

    public function status(Request $request)
    {

        // dd($request);

        $date = new DateTime();

        $data_usage = DB::connection('mysql_radius')->table('radacct')
                                ->select('username', 'acctstarttime', 'acctstoptime', DB::raw('SEC_TO_TIME(acctsessiontime) as acctsessiontime'), DB::raw('((acctinputoctets)/1000/1000/1000) as acctinputoctets'), DB::raw('((acctoutputoctets)/1000/1000/1000) as acctoutputoctets'))
                                ->where('username', $request->username)
                                // ->whereDate('acctstarttime', '<=', $date->format('Y-m-d'))
                                ->whereDate('acctstarttime', '>=', $date->modify('-1 month')->format('Y-m-d'))
                                // ->groupBy(DB::raw('DATE(acctstarttime)'))
                                ->get();

        // dd($data_usage);
                
        return view('hotspot.status', ['data_usage' => $data_usage, 'request' => $request->all()]);
    }

    public function privacy(Request $request)
    {
        return view('hotspot.privacy');
    }

    public function termsOfService(Request $request)
    {
        return view('hotspot.termsOfService');
    }

    
    
    public function create(Request $request)
    {
        return view('hotspot.register',['request' => $request]);
    }

    public function daftar(Request $request)
    {
        //deklarasi validasi

        $rules = [
            'nik' => ['required', 'numeric'],
            'username' => ['required', 'alpha_num'],
            'password' => ['required', 'alpha_num', 'min:8'],
            'foto_ktp' => ['required', 'file', 'image', 'max:6000']
        ];

        $messages = [
            'nik.required' => 'NIK harus diisi',
            'nik.numeric' => 'NIK harus angka',
            'username.required' => 'Username harus diisi',
            'username.alpha_num' => 'Username harus berupa angka atau huruf atau kombinasi tanpa spasi',
            'password.required' => 'Password harus diisi',
            'password.alpha_num' => 'Password harus berupa angka atau huruf atau kombinasi tanpa spasi',
            'password.min' => 'Password harus minimal 8 karakter',
        ];


        //validasi
        $validator = Validator::make($request->all(), $rules, $messages);
        
        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();

        try {
            //verifikasi nik
            $cek = DB::connection('mysql')->table('tb_nik')->select('id')->where('nik',$request->nik)->first();
            
            if($cek == null){
                Session::flash('error', 'NIK Tidak Terdaftar!');
                return redirect()->back();
            }

            //check if exists
            $cek_ada = DB::connection('mysql')->table('tb_user_hotspot')->where('nik_id',$cek->id)->first();

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

            } elseif($age > 14 && $age < 21){
                $kategori = "Remaja";
                
            } else {
                $kategori = "Anak";
            }

            if($request->hasFile('foto_ktp')){

                $folder = public_path('storage/ktp/'.date('d_m_Y'));
                
                if(!File::exists($folder)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                $file = $request->file('foto_ktp');

                $dir = 'ktp/'.date('d_m_Y');
                $path = Storage::disk('public')->putFile($dir, $file);

                // $file = $request->file('foto_ktp');
                // $path = Storage::disk('public')->putFile('ktp', $file);
            }
            

            //buat akun di DB local
            $user_local = DB::connection('mysql')->table('tb_user_hotspot')
                            ->insertGetId([
                                    'nik_id' => $cek->id,
                                    'username' => $request->username,
                                    // 'password' => $request->password,
                                    'kategori' => $kategori,
                                    'mac' => $request->mac,
                                    'ip' => $request->ip,
                                    'isDeleted' => 0,
                                    'path' => $path,
                                    'created_at' => date('Y-m-d'),
                                ]);

            $kategori_umum = DB::connection('mysql')->table('tb_kategori_user')
                                ->where('group', 'masyarakat_umum')
                                ->where('status', 1)
                                ->first();

            DB::connection('mysql')->table('tb_det_kategori_user')
                ->insert([
                        'id_kategori_user' => $kategori_umum->id,
                        'id_user_social' => null,
                        'id_user_hotspot' => $user_local,
                    ]);

            $radcheck = DB::connection('mysql_radius')->table('radcheck')->insert([
                'username' => $request->username,
                'attribute' => 'Cleartext-Password',
                'op' => ':=',
                'value' => $request->password,
            ]);

            $radusergroup = DB::connection('mysql_radius')->table('radusergroup')->insert([
                'username' => $request->username,
                'groupname' => $kategori_umum->group,
                'priority' => 10,
            ]);

            
            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();

            return view('hotspot/loginAfterRegister',['username' => $request->username, 'password' => $request->password]);

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();

            dd($th);
        }
        
    }


    public function createKeluhan(Request $request)
    {
        return view('hotspot.keluhan');
    }

    public function postKeluhan(Request $request)
    {
        $request->validate([
            'nik' => ['required'],
            'nama' => ['required'],
            'isi' => ['required'],
        ]);

        //cek NIK
        $cek = DB::connection('mysql')->table('tb_nik')->select('id')->where('nik',$request->nik)->first();
        
        if($cek == null){
            Session::flash('error', 'NIK Tidak Terdaftar!');
            return redirect()->back();

        } else {
            // Insert to DB
            DB::connection('mysql')->table('tb_keluhan')->insert([
                'nik_id' => $cek->id,
                'nama' => $request->nama,
                'isi' => $request->isi,
                'read' => 0,
            ]);

            Session::flash('success', 'Pesan sudah dikirim!');
            return redirect()->back();
        }
        
    }


    public function showforgotPassword(Request $request)
    {
        return view('hotspot.forgotPassword');
    }
    
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'nik' => ['required'],
            'username' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        //change password 
        DB::connection('mysql_radius')->beginTransaction();
        try {

            //cek NIK
            $user = DB::connection('mysql')->table('tb_user_hotspot')
                    ->join('tb_nik', 'tb_user_hotspot.nik_id', '=', 'tb_nik.id')
                    ->where('tb_nik.nik', $request->nik)
                    ->where('tb_user_hotspot.username', $request->username)
                    ->first();
            
            if(empty($user)){

                return redirect()->back()->with('error', 'Username dan NIK tidak sesuai!');

            } else {

                DB::connection('mysql_radius')->table('radcheck')
                    ->where('username', $user->username)
                    ->update([
                        'value' => $request->password,
                    ]);

                DB::connection('mysql_radius')->commit();
                return redirect()->back()->with('success', 'Password berhasil diubah!');
            }

        } catch (\Throwable $th) {

            DB::connection('mysql_radius')->rollback();
            dd($th);

        }
    }

    public function showForgetUsername(Request $request)
    {
        return view('hotspot.findUsername');
    }

    public function findUsername(Request $request)
    {
        $request->validate([
            'nik' => ['required'],
        ]);

        $username = DB::connection('mysql')->table('tb_user_hotspot')
                        ->join('tb_nik','tb_user_hotspot.nik_id', '=', 'tb_nik.id')
                        ->select('tb_user_hotspot.username')
                        ->where('tb_nik.nik', $request->nik)
                        ->first();
        
        $username = $username->username;
        
        if(isset($username)){
            $mask_username = str_repeat('*', strlen($username) - 4) . substr($username, -4);
            return view('hotspot.findUsername', ['username' => $mask_username, 'nik' => $request->nik]);
            
        } else {
            Session::flash('error', 'Username tidak ditemukan!');
            return view('hotspot.findUsername', ['nik' => $request->nik]);
        }

        
    }




    public function resubmitForm(Request $request)
    {    
        //cek link
        $rejected = DB::connection('mysql')->table('tb_link_rejected')->where('token', $request->tokenID)->first();
        
        if(!is_null($rejected)){

            $data = DB::connection('mysql')->table('tb_user_hotspot')->where('id', $rejected->id_user_hotspot)->first();

            //return view nya
            return view('hotspot/resubmit',['user' => $data]);

        } else {

            //error
            return redirect()->back()->with('error', 'Link tidak valid!');

        }
    }


    public function resubmit(Request $request)
    {
        //upload foto
        $data = $request->validate([
            'id_user' => ['required'],
            'foto_ktp' => ['required', 'file', 'image', 'max:6000'],
        ]);

        try {

            if($request->hasFile('foto_ktp')){

                $folder = public_path('storage/ktp/'.date('d_m_Y'));
                
                if(!File::exists($folder)) {
                    File::makeDirectory($path, 0777, true, true);
                }

                $file = $request->file('foto_ktp');

                $dir = 'ktp/'.date('d_m_Y');
                $path = Storage::disk('public')->putFile($dir, $file);
                
                DB::connection('mysql')->table('tb_user_hotspot')
                    ->where('id', $request->id_user)
                    ->update([
                        'path' => $path,
                        'status' => 0,
                    ]);
                
            }

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    public function test()
    {
        $folder = public_path('storage/ktp/'.date('d_m_Y'));
        dd($folder);
    }


    

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {

            //get the request
            // $request = Session::get('request');
            
            $user = Socialite::driver($provider)->stateless()->user();

            // dd($user);

            $username = preg_replace('/\s+/', '_', $user->name);

            $password = time();
            
            // dd($user);

            /// lakukan pengecekan apakah facebook/google id nya sudah ada apa belum
            $user_db = DB::connection('mysql')->table('tb_user_social')->where('social_id', $user->id)->first();
            
            if(is_null($user_db)){

                $cek_username = DB::connection('mysql')->table('tb_user_social')->select('username')->where('username', $username)->first();
                
                if(!is_null($cek_username)){
                    $username = $cek_username->username . "_" . $provider;
                }

                DB::connection('mysql')->beginTransaction();
                DB::connection('mysql_radius')->beginTransaction();

                try {

                    $social = DB::connection('mysql')->table('tb_user_social')->insertGetId([
                                    'social_id' => $user->id,
                                    'nama' => $user->name,
                                    'username' => $username,
                                    'email' => $user->email,
                                    'password' => $password,
                                    'platform' => $provider,
                                    'created_at' => date('Y-m-d'),
                                    'isDeleted' => 0,
                                ]);

                    
                    $kategori_sosmed = DB::connection('mysql')->table('tb_kategori_user')
                                        ->where('group', 'social_media')
                                        ->where('status', 1)
                                        ->first();

                    DB::connection('mysql')->table('tb_det_kategori_user')
                        ->insert([
                                'id_kategori_user' => $kategori_sosmed->id,
                                'id_user_social' => $social,
                                'id_user_hotspot' => null,
                            ]);
                                

                    DB::connection('mysql_radius')->table('radcheck')
                        ->insert([
                                'username' => $username,
                                'attribute' => 'Cleartext-Password',
                                'op' => ':=',
                                'value' => $password,
                            ]);
    
                    DB::connection('mysql_radius')->table('radusergroup')
                        ->insert([
                                'username' => $username,
                                'groupname' => $kategori_sosmed->group,
                                'priority' => 10,
                            ]);

                    // $radreply = DB::connection('mysql_radius')->table('radreply')->insert([
                    //                 ['username' => $username,
                    //                 'attribute' => 'Mikrotik-Rate-Limit',
                    //                 'op' => ':=',
                    //                 'value' => '4M/4M 0/0 0/0 0/0 8 2M/2M'],

                    //                 ['username' => $username,
                    //                 'attribute' => 'Session-Timeout',
                    //                 'op' => ':=',
                    //                 'value' => '3600'],
                    //             ]);

                    DB::connection('mysql')->commit();
                    DB::connection('mysql_radius')->commit();
     
                    return view('hotspot/loginAfterRegister',['username' => $username, 'password' => $password]);


                } catch (\Exception $e) {
                    DB::connection('mysql')->rollback();
                    DB::connection('mysql_radius')->rollback();
                    
                    Session::flash('error', 'Terjadi kesalahan saat upload ke database');
                    return view('hotspot/register');
                    // dd($e->getMessage());
                }
                
 
            } else {

                if($user_db->isDeleted == 1){
                    Session::flash('error', 'Akun Anda telah di Non-Aktifkan oleh Admin');
                    return view('hotspot/register');
                } else {
                    return view('hotspot/loginAfterRegister',['username' => $user_db->username, 'password' => $user_db->password]);
                }
                
            }


        } catch (\Exception $e) {
            Session::flash('error', 'Terjadi kesalahan saat request data akun');
            dd($e->getMessage());
        }
    }


    public function deleteCallbackFacebook(Request $request)
    {

        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();

        try {
            
            $signed_request = $request->get('signed_request');
            $data = $this->parse_signed_request($signed_request);
            $user_id = $data['user_id'];

            //get username
            $username = DB::connection('mysql')->table('tb_user_social')
                            ->select('username')
                            ->where('social_id',$user_id)->first();

            if(!is_null($username)){

                $code = $this->random_strings(5);


                    $deleteLaravel = DB::connection('mysql')->table('tb_user_social')
                                            ->where('social_id', $user_id)->delete();
    
    
                    $deleteRadiusUser = DB::connection('mysql_radius')->table('radcheck')
                                            ->where('username', $username)->delete();
    
                                            
                    $deleteRadiusGroup = DB::connection('mysql_radius')->table('radgroup')
                                            ->where('username', $username)->delete();
                    

                    $createTicket = DB::connection('mysql')->table('tb_deletion_ticket')
                                            ->insert([
                                                'ticket' => $code,
                                                'status' => "Success",
                                                'platform' => "facebook",
                                                'created_at' => date('Y-m-d'),
                                                'updated_at' => null,
                                            ]);

    
                    if ($deleteLaravel && $deleteRadiusGroup && $deleteRadiusUser) {
                        
                        DB::connection('mysql')->commit();
                        DB::connection('mysql_radius')->commit();

                        return response()->json([
                            'url' => route('user.facebook.delete.track', ['code' => $code]), // <------ i dont know what to put on this or what should it do
                            'confirmation_code' => $code, // <------ i dont know what is the logic of this code
                        ]);
                    }

            }

            // here will delete the user base on the user_id from facebook

            return response()->json([
                'message' => 'operation not successful'
            ], 500);


        } catch (\Execption $th) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();
            dd($th);
        }
        
    }



    private function random_strings($length_of_string) {
  
        // random_bytes returns number of bytes
        // bin2hex converts them into hexadecimal format
        return substr(bin2hex(random_bytes($length_of_string)), 
                                          0, $length_of_string);
    }



    private function parse_signed_request($signed_request)
    {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        $secret = $_ENV('FACEBOOK_SECRET');// Use your app secret here

        // decode the data
        $sig = $this->base64_url_decode($encoded_sig);
        $data = json_decode($this->base64_url_decode($payload), true);

        // confirm the signature
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }


    public function deleteTracker(Request $request, $code)
    {
        $confirmation_code = $code;

        // $data = DB::connection('mysql')->table('tb_deletion_ticket')->where('ticket',$confirmation_code)->first();

        // return view('hotspot.deletion', ['$data' => $data]);
        return view('hotspot.deletion');
    }


    // API START HERE //
    public function getUsername(Request $request)
    {
    
        return json_encode([
            'status' => 200,
            'message' => 'valid',
            'nik' => $request->nik,
            'username' => $request->username,
        ]);


        //cek nik
        $nik = DB::connection('mysql')->table('tb_nik')->where('nik',$request->nik)->first();

        if(isset($nik)){
            
            $user = DB::connection('mysql')->table('tb_user_hotspot')->where('nik_id', $nik->id)->first();

            if($user->username == $request->username){

                return json_encode([
                    'status' => 200,
                    'message' => 'valid',
                    'id_akun' => $user->id,
                ]);

            }
            
            return json_encode([
                'status' => 500,
                'message' => 'Username Salah',
            ]);

        }

        return json_encode([
            'status' => 500,
            'message' => 'User tidak ada',
        ]);


    }


    public function getKategori(Request $request)
    {
        // Get Kategori

        $user = DB::connection('mysql')->table('tb_user_hotspot')->select('kategori', 'isDeleted')->where('username', $request->username)->first();

        return json_encode([
            'status' => 200,
            'data' => [
                'kategori' => $user->kategori,
                'isDeleted' => $user->isDeleted,
            ],
        ]);


    }

    public function getSocialStatus(Request $request)
    {
        $user = DB::connection('mysql')->table('tb_user_social')->select('isDeleted')->where('username', $request->username)->first();

        return json_encode([
            'status' => 200,
            'data' => [
                'isDeleted' => $user->isDeleted,
            ],
        ]);
    }

    public function cekValidasi(Request $request)
    {
        $user = DB::connection('mysql')->table('tb_user_hotspot')
                ->where('username', $request->username)
                ->first();

        if($user->status == 2){
            $data = DB::connection('mysql')->table('tb_link_rejected')
                ->where('id_user_hotspot', $user->id)
                ->where('status', 1)
                ->first();
            $link = $data->link;
        } else {
            $link = null;
        }


        return json_encode([
            'status' => 200,
            'data' => [
                'status' => $user->status,
                'link' => $link,
            ],
        ]);

    }



}
