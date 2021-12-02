<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Facades\DB;
use Session;
use Laravel\Socialite\Facades\Socialite;
use Validator;

class LoginController extends Controller
{

    public function index(Request $request)
    {
        return view('hotspot/login', ['request' => $request->all()]);
    }

    public function redirectStatus()
    {
        $url = "https://mikrotik.lpk-resortkuta.com/status";

        return redirect($url);
    }

    public function status(Request $request)
    {

        $date = new DateTime();

        $data_usage = DB::connection('mysql_radius')->table('radacct')
                        ->select('username', 'acctstarttime', 'acctstoptime', DB::raw('(SUM(acctinputoctets)/1000/1000/1000) as acctinputoctets'), DB::raw('(SUM(acctoutputoctets)/1000/1000/1000) as acctoutputoctets'))
                        ->groupBy('acctstarttime')
                        ->where('username', $request->username)
                        ->whereDate('acctstarttime', '<=', $date->format('Y-m-d'))
                        ->whereDate('acctstarttime', '>=', $date->modify('-6 day')->format('Y-m-d'))
                        ->get();
                
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
        return view('hotspot/register',['request' => $request]);
    }

    public function daftar(Request $request)
    {
        //deklarasi validasi

        $rules = [
            'nik' => ['required', 'numeric'],
            'username' => ['required', 'alpha_num'],
            'password' => ['required', 'alpha_num', 'min:8'],
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

        // $request->validate([
        //     'nik' => ['required', 'numeric'],
        //     'username' => ['required', 'alpha_num'],
        //     'password' => ['required', 'alpha_num', 'min:8'],
        // ]);

        
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

            //buat akun di DB local
            $user_local = DB::connection('mysql')->table('tb_user_hotspot')
                            ->insertGetId([
                                    'nik_id' => $cek->id,
                                    'username' => $request->username,
                                    // 'password' => $request->password,
                                    'kategori' => $kategori,
                                    'mac' => $request->mac,
                                    'ip' => $request->ip,
                                    'created_at' => date('Y-m-d'),
                                    'isDeleted' => 0,
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

            

                //    $radreply = DB::connection('mysql_radius')->table('radreply')->insert([
                //        'username' => $request->username,
                //        'attribute' => 'Mikrotik-Rate-Limit',
                //        'op' => ':=',
                //        'value' => '8M/8M 0/0 0/0 0/0 4 4M/4M',
                //    ]);

            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();

            return view('hotspot/loginAfterRegister',['username' => $request->username, 'password' => $request->password]);
            // $request->session()->put('userSuccess', 'Akun Berhasil dibuat!, Silahkan Login');
            // return redirect()->intended('http://10.0.0.1/');

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();

            dd($th);
        }
        
    }


    public function showforgotPassword(Request $request)
    {
        return view('hotspot.forgotPassword');
    }

    
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        //change password 
        DB::connection('mysql_radius')->beginTransaction();
        try {
            $user = DB::connection('mysql')->table('tb_user_hotspot')->where('id', $request->id_akun)->first();

            DB::connection('mysql_radius')->table('radcheck')->where('username', $user->username)
                        ->update([
                            'value' => $request->password,
                        ]);
            
            DB::connection('mysql_radius')->commit();

        } catch (\Throwable $th) {

            DB::connection('mysql_radius')->rollback();
            dd($th);

        }
    }

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

}
