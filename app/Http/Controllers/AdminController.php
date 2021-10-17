<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use Illuminate\Support\Str;
use DateTime;
use Hash;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{

    public function dashboard(Request $request)
    {   

        $userBaruOrganik = DB::connection('mysql')->table('tb_user_hotspot')
                                ->whereDate('created_at', date('Y-m-d'))
                                ->count();

        $userBaruSocial = DB::connection('mysql')->table('tb_user_social')
                                ->whereDate('created_at', date('Y-m-d'))
                                ->count();

        $userOrganik = DB::connection('mysql')->table('tb_user_hotspot')
                                ->count();
         
        $userSocial = DB::connection('mysql')->table('tb_user_social')
                                ->count();

        $response = app('App\Http\Controllers\API\MikrotikController')->checkActiveUserHotspot();
        $response = json_decode($response);
        $totalAktif = count($response->data->users);

        $data = [
            'userBaru' => $userBaruOrganik + $userBaruSocial,
            'totalUser' => $userOrganik + $userSocial,
            'totalAktif' => $totalAktif,
        ];

        return view('admin.dashboard.dashboardAdmin', ['data' => $data]);
    }

    public function showFormLogin()
    {
        return view('admin.login.loginAdmin');
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            DB::connection('mysql')->table('users')
                ->where('id', Auth::id())
                ->update(['api_token' => Str::random(60)]);
        
            $request->session()->flash('success', 'Selamat Datang, ' . Auth::user()->nama );
            return redirect()->intended(route('admin.dashboard'));

        } else {
            return back()->withErrors([
                'error' => 'Username dan Password tidak cocok',
            ]);
        }
    }


    public function showCreateAccount(Request $request)
    {
        return view('admin.account.createAccount');
    }

    public function createAccount(Request $request)
    {
        $data = $request->validate([
            'nip' => ['required'],
            'nama' => ['required'],
            'username' => ['required', 'alpha_num'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'numeric'],
        ]);

        DB::transaction(function() use(&$data) {
            $admin = DB::connection('mysql')->table('users')->insert([
                'nip' => $data['nip'],
                'nama' => $data['nama'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if($admin){
                // $request->session()->flash('success', 'Sukses bos!');
                return redirect(route('admin.account'))->with('success', 'Akun berhasil dibuat!');
            }
        });

    }


    public function listAccount(Request $request)
    {
        $accounts = DB::connection('mysql')->table('users')->select('id','nip','nama','role', 'isDeleted')->get();
        return view('admin.account.listAccount',['users' => $accounts]);
    }

    public function editAccount(Request $request)
    {

        $user = DB::connection('mysql')->table('users')->select('id','nip','username','email','nama','role')->find($request->id);
        
        return view('admin.account.createAccount',['user' => $user]);

    }

    public function updateAccount(Request $request)
    {
        $data = $request->validate([
            'nip' => ['required'],
            'nama' => ['required'],
            'username' => ['required', 'alpha_num'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'role' => ['required', 'numeric'],
            'id' => ['required', 'numerid'],
        ]);


        DB::beginTransaction();
        try {
            $update = DB::connection('mysql')->table('users')
                        ->where('id', $data['id'])
                        ->update([
                            'nip' => $data['nip'],
                            'nama' => $data['nama'],
                            'username' => $data['username'],
                            'email' => $data['email'],
                            'role' => $data['role'],
                        ]);
            
            DB::commit();
        
            return redirect(route('admin.account'))->with('success', 'Akun berhasil diupdate!');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Hmm.. Ada yang salah..');
        }
    }

    public function deleteAccount(Request $request)
    {
        try {
            $delete = DB::connection('mysql')->table('users')->where('id',$request->id)->update(['isDeleted' => 1]);
            
            if($delete){
                return redirect(route('admin.account'))->with('success', 'Akun berhasil di Non-Aktifkan!');
            }
        } catch (\Throwable $th) {

            return redirect(route('admin.account'))->with('error', 'Hmm.. Sepertinya ada yang salah!');
        }
    }


    public function enableAccount(Request $request)
    {
        try {
            $enable = DB::connection('mysql')->table('users')->where('id',$request->id)->update(['isDeleted' => 0]);
            
            if($enable){
                return redirect(route('admin.account'))->with('success', 'Akun berhasil di Aktifkan!');
            }
        } catch (\Throwable $th) {

            return redirect(route('admin.account'))->with('error', 'Hmm.. Sepertinya ada yang salah!');
        }
    }

    //list hotspot user
    public function hotspotUser(Request $request, $user)
    {
        if($user == 'organik'){

            $Users = DB::connection('mysql')->table('tb_user_hotspot')
                     ->join('tb_nik', 'tb_user_hotspot.nik_id', '=', 'tb_nik.id')
                    //  ->select('tb_nik.nama', 'tb_user_hotspot.*')
                     ->get();

            return view('admin.hotspotUser.userRadius', ['users' => $Users]);

        } else {

            $Users = DB::connection('mysql')->table('tb_user_social')->where('platform', $user)->get();

            return view('admin.hotspotUser.userRadius2', ['users' => $Users]);
        }
    }

    //disable user
    public function deleteUser(Request $request)
    {

        $tabel = $request->user == 'organik' ? 'tb_user_hotspot' : 'tb_user_social';

        try {
            $delete = DB::connection('mysql')->table($tabel)->where('id',$request->id)->update(['isDeleted' => 1]);
            
            if($delete){
                return redirect(route('admin.user',['user' => $request->user]))->with('success', 'Akun berhasil di Non-Aktifkan!');
            }
        } catch (\Throwable $th) {
            return redirect(route('admin.user',['user' => $request->user]))->with('error', 'Hmm.. Sepertinya ada yang salah!');
        }
    }

    // enable user
    public function enableUser(Request $request)
    {

        $tabel = $request->user == 'organik' ? 'tb_user_hotspot' : 'tb_user_social';

        try {
            $delete = DB::connection('mysql')->table($tabel)->where('id',$request->id)->update(['isDeleted' => 0]);
            
            if($delete){
                return redirect(route('admin.user',['user' => $request->user]))->with('success', 'Akun berhasil di Aktifkan!');
            }
        } catch (\Throwable $th) {
            dd($th);
            return redirect(route('admin.user',['user' => $request->user]))->with('error', 'Hmm.. Sepertinya ada yang salah!');
        }
    }

    public function reportUsage(Request $request, $range)
    {
        if(in_array($range, ['weekly', 'monthly', 'yearly'])){
            
            return view('admin.report.bandwidthReport',['range' => $range]);

        } else {

            abort(404);

        }
        
    }


    // ALL API for report start here

    public function apiDataPertumbuhanPengguna($range, $tgl)
    {
        try {

            $date_now = DateTime::createFromFormat('dmY', $tgl);
            // dd($date);

            $year = $date_now->format('Y');
            $month = $date_now->format('m');
            $week = $date_now->format('W');

            $arr_data = [];
            $arr_label = [];
            
            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                for ($i=0; $i < 7; $i++) {

                    $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                                            ->whereDate('created_at',$date->format('Y-m-d'))->count();

                    $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                                            ->whereDate('created_at',$date->format('Y-m-d'))->count();

                    // $date = date('Y-m-d', strtotime());
                    // array_push($array, [ "data" => $userOrganikCount + $userSocialCount, "label" => $date->format('Y-m-d')]);
                    array_push($arr_data, $userOrganikCount + $userSocialCount);
                    array_push($arr_label, $date->format('Y-m-d'));
                    

                    $date->modify('+1 day');

                }


            } elseif($range == 'monthly'){
                
                $date = new DateTime;

                for ($i=0; $i < 4; $i++) { 
                    $date->setISODate($year, $week);
                    $senin = $date->format('Y-m-d');

                    $date->modify("sunday this week");
                    $minggu = $date->format('Y-m-d');
                    
                    $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                                            ->whereBetween('created_at',[$senin,$minggu])->count();

                    $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                                            ->whereBetween('created_at',[$senin,$minggu])->count();

                    array_push($arr_data, $userOrganikCount + $userSocialCount);
                    array_push($arr_label, 'Minggu ke '.$i+1);

                    $week++;

                }

            } elseif($range == 'yearly'){

                $date = new DateTime;
                $date->setISODate($year, $week);
               
                for ($i=1; $i <= 12 ; $i++) {

                    $month = $date->format('n');
                    
                    $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                                            ->whereMonth('created_at',$i)->whereYear('created_at', $year)->count();

                    $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                                            ->whereMonth('created_at',$i)->whereYear('created_at', $year)->count();

                    array_push($arr_data, $userOrganikCount + $userSocialCount);
                    array_push($arr_label, $date->format('F'));

                    $date->modify('+1 Month');

                }
                
            }

            return response()->json([
                'status' => 200,
                'data' => [
                    'data' => $arr_data,
                    'label' => $arr_label,
                    ],
            ]);


        } catch (\Execption $th) {
            dd($th);
        }
    }

    public function apiDataPenggunaan($range, $tgl)
    {   
        try {
            
            $date_now = DateTime::createFromFormat('dmY', $tgl);
            // dd($date);

            $year = $date_now->format('Y');
            $month = $date_now->format('m');
            $week = $date_now->format('W');

            $arr_data = [];
            $arr_label = [];

            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                $array = [];

                for ($i=0; $i < 7; $i++) {

                    $penggunaanTotal = DB::connection('mysql_radius')->table('data_usage_by_period')
                                            ->select(DB::raw('((SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000)) as GB_total'))
                                            ->whereNotNull('period_end')
                                            ->whereDate('period_start', $date->format('Y-m-d'))
                                            ->groupBy('period_start')
                                            ->get();
                    
                    
                    array_push($arr_data, $penggunaanTotal->isEmpty() ? 0 : number_format(floatval($penggunaanTotal[0]->GB_total) , 2 ,'.' , '') );
                    array_push($arr_label, $date->format('Y-m-d'));

                    $date->modify('+1 day');
                }
                // dd($arr_data);

            } elseif($range == 'monthly'){
                
                $date = new DateTime;

                for ($i=0; $i < 4; $i++) { 

                    $date->setISODate($year, $week);
                    $senin = $date->format('Y-m-d');

                    $date->modify("sunday this week");
                    $minggu = $date->format('Y-m-d');
                    
                    $penggunaanTotal = DB::connection('mysql_radius')->table('data_usage_by_period')
                                            ->select(DB::raw('(SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000) as GB_total'))
                                            ->whereNotNull('period_end')
                                            ->whereBetween('period_start',[$senin,$minggu])
                                            ->groupBy(DB::raw('WEEK(period_start)'))->get();

                    array_push($arr_data, $penggunaanTotal->isEmpty() ? 0 : number_format(floatval($penggunaanTotal[0]->GB_total) , 2 ,'.' , '') );
                    array_push($arr_label, 'Minggu ke '.$i+1);

                    $week++;

                }

            } elseif($range == 'yearly'){

                $date = new DateTime;
                $date->setISODate($year, $week);
                
                for ($i=1; $i <= 12 ; $i++) {
                    
                    $penggunaanTotal = DB::connection('mysql_radius')->table('data_usage_by_period')
                                        ->select(DB::raw('(SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000) as GB_total'))
                                        ->whereNotNull('period_end')
                                        ->whereMonth('period_start',$i)
                                        ->whereYear('period_start', $year)
                                        ->groupBy(DB::raw('MONTH(period_start)'))->get();
                   
                    array_push($arr_data, $penggunaanTotal->isEmpty() ? 0 : number_format(floatval($penggunaanTotal[0]->GB_total) , 2 ,'.' , '') );
                    array_push($arr_label, $date->format('F'));

                    $date->modify('+1 month');
                }
            }


            return response()->json([
                'status' => 200,
                'data' => [
                    'data' => $arr_data,
                    'label' => $arr_label,
                    ],
            ]);


        } catch (\Execption $th) {
            dd($th);
        }
        
    }

    public function apiProporsiPlatform($range, $tgl = null)
    {
        try {
            
            if(!is_null($tgl)){

                $date_now = DateTime::createFromFormat('dmY', $tgl);
                // dd($date);
    
                $year = $date_now->format('Y');
                $month = $date_now->format('m');
                $week = $date_now->format('W');

            }

            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                $senin = $date->format('Y-m-d');
                
                $date->modify('sunday this week');

                $minggu = $date->format('Y-m-d');

                
                $organik = DB::connection('mysql')->table('tb_user_hotspot')
                            ->whereBetween('created_at',[$senin,$minggu])->count();

                $facebook = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','facebook')
                            ->whereBetween('created_at',[$senin,$minggu])->count();
                            
                $google = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','google')
                            ->whereBetween('created_at',[$senin,$minggu])->count();

                $array = [$organik,$facebook,$google];


            } elseif($range == 'monthly'){

                $organik = DB::connection('mysql')->table('tb_user_hotspot')
                            ->whereMonth('created_at',$month)->count();
                            
                $facebook = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','facebook')
                            ->whereMonth('created_at',$month)->count();

                $google = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','google')
                            ->whereMonth('created_at',$month)->count();

                $array = [$organik,$facebook,$google];

            } elseif($range == 'yearly'){

                $organik = DB::connection('mysql')->table('tb_user_hotspot')
                            ->whereYear('created_at',$year)->count();

                $facebook = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','facebook')
                            ->whereYear('created_at',$year)->count();

                $google = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','google')
                            ->whereYear('created_at',$year)->count();

                $array = [$organik,$facebook,$google];

            } elseif($range == 'all'){

                $organik = DB::connection('mysql')->table('tb_user_hotspot')->count();

                $facebook = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','facebook')->count();

                $google = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','google')->count();

                $array = [$organik,$facebook,$google];

            }



            return response()->json([
                'status' => 200,
                'data' => $array,
            ]);


        } catch (\Execption $th) {
            dd($th);
        }
    }

    public function apiProporsiUmur($range, $tgl = null)
    {
        try {
            
            if(!is_null($tgl)){

                $date_now = DateTime::createFromFormat('dmY', $tgl);
                // dd($date);
    
                $year = $date_now->format('Y');
                $month = $date_now->format('m');
                $week = $date_now->format('W');

            }

            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                $senin = $date->format('Y-m-d');
                
                $date->modify('sunday this week');

                $minggu = $date->format('Y-m-d');

                

                $dewasa = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','dewasa')
                            ->whereBetween('created_at',[$senin,$minggu])->count();

                $remaja = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','remaja')
                            ->whereBetween('created_at',[$senin,$minggu])->count();

                $anak = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','anak')
                            ->whereBetween('created_at',[$senin,$minggu])->count();

                $array = [$dewasa,$remaja,$anak];


            } elseif($range == 'monthly'){

                $dewasa = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','dewasa')
                            ->whereMonth('created_at',$month)->count();

                $remaja = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','remaja')
                            ->whereMonth('created_at',$month)->count();

                $anak = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','anak')
                            ->whereMonth('created_at',$month)->count();

                $array = [$dewasa,$remaja,$anak];

            } elseif($range == 'yearly'){

                $dewasa = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','dewasa')
                            ->whereYear('created_at',$year)->count();

                $remaja = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','remaja')
                            ->whereYear('created_at',$year)->count();

                $anak = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','anak')
                            ->whereYear('created_at',$year)->count();

                $array = [$dewasa,$remaja,$anak];

            } elseif($range == 'all'){

                $dewasa = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','dewasa')->count();

                $remaja = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','remaja')->count();

                $anak = DB::connection('mysql')->table('tb_user_hotspot')
                            ->where('kategori','anak')->count();

                $array = [$dewasa,$remaja,$anak];
            }


            return response()->json([
                'status' => 200,
                'data' => $array,
            ]);


        } catch (\Execption $th) {
            dd($th);
        }
    }

    public function apiPenggunaanPerUser($range, $tgl)
    {
        try {
            
            $date_now = DateTime::createFromFormat('dmY', $tgl);
            // dd($date);

            $year = $date_now->format('Y');
            $month = $date_now->format('m');
            $week = $date_now->format('W');

            $array = [];

            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                $senin = $date->format('Y-m-d');
                
                $date->modify('sunday this week');

                $minggu = $date->format('Y-m-d');


                $users_radius = DB::connection('mysql_radius')->table('data_usage_by_period')
                        ->select('username', DB::raw('(SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000) AS GB_total'))
                        ->whereNotNull('period_end')
                        ->whereBetween('period_start',[$senin,$minggu])
                        ->groupBy('username')
                        ->orderBy('GB_total', 'desc')->get();

                $i = 0;
                foreach ($users_radius as $user) {

                    $i++;
                    
                    $detail = DB::connection('mysql')->table('tb_user_hotspot')
                                ->where('username',$user->username)->first();
                    
                    if(is_null($detail)){

                        $detail = DB::connection('mysql')->table('tb_user_social')
                                    ->where('username',$user->username)->first();
                        
                        $platform = $detail->platform;
                        $kategori = '-';

                    } else {

                        $platform = "Organik";
                        $kategori = $detail->kategori ;

                    }

                    
                    $det_user = [
                        'no' => $i,
                        'username' => $user->username,
                        'kategori' => $kategori,
                        'platform' => $platform,
                        'penggunaan' => empty($user->GB_total) ? 0 : number_format(floatval($user->GB_total) , 2 ,'.' , '') ,
                    ];

                    array_push($array, $det_user);
                }


            } elseif($range == 'monthly'){

                $users_radius = DB::connection('mysql_radius')->table('data_usage_by_period')
                        ->select('username', DB::raw('(SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000) AS GB_total'))
                        ->whereNotNull('period_end')
                        ->whereMonth('period_start', $month)
                        ->groupBy('username')
                        ->orderBy('GB_total', 'desc')->get();

                $i = 0;
                foreach ($users_radius as $user) {

                $i++;
                    
                    $detail = DB::connection('mysql')->table('tb_user_hotspot')
                                ->where('username',$user->username)->first();
                    
                    if(is_null($detail)){

                        $detail = DB::connection('mysql')->table('tb_user_social')
                        ->where('username',$user->username)->first();
                        
                        $platform = $detail->platform;
                        $kategori = '-';

                    } else {

                        $platform = "Organik";
                        $kategori = $detail->kategori ;

                    }

                    
                    $det_user = [
                        'no' => $i,
                        'username' => $user->username,
                        'kategori' => $kategori,
                        'platform' => $platform,
                        'penggunaan' => empty($user->GB_total) ? 0 : number_format(floatval($user->GB_total) , 2 ,'.' , ''),
                    ];

                    array_push($array, $det_user);
                }

            } elseif($range == 'yearly'){

                $users_radius = DB::connection('mysql_radius')->table('data_usage_by_period')
                        ->select('username', DB::raw('(SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000) AS GB_total'))
                        ->whereNotNull('period_end')
                        ->whereYear('period_start', $year)
                        ->groupBy('username')
                        ->orderBy('GB_total', 'desc')->get();

                $i = 0;
                foreach ($users_radius as $user) {
                
                $i++;

                    $detail = DB::connection('mysql')->table('tb_user_hotspot')
                                ->where('username',$user->username)->first();

                    if(is_null($detail)){

                        $detail = DB::connection('mysql')->table('tb_user_social')
                        ->where('username',$user->username)->first();
                        
                        $platform = $detail->platform;
                        $kategori = '-';

                    } else {

                        $platform = "Organik";
                        $kategori = $detail->kategori ;

                    }

                    
                    $det_user = [
                        'no' => $i,
                        'username' => $user->username,
                        'kategori' => $kategori,
                        'platform' => $platform,
                        'penggunaan' => empty($user->GB_total) ? 0 : number_format(floatval($user->GB_total) , 2 ,'.' , ''),
                    ];

                    array_push($array, $det_user);
                }

            }

            return response()->json([
                'data' => $array,
            ]);


        } catch (\Execption $th) {
            dd($th);
        }
    }


    public function apiWaktuPenggunaan($range, $tgl)
    {
        try {

            $date_now = DateTime::createFromFormat('dmY', $tgl);
            // dd($date);
    
            $year = $date_now->format('Y');
            $month = $date_now->format('m');
            $week = $date_now->format('W');
    
            // Buat array panjangnya 24
            $array = array_fill(0,24,0);
    
            if($range == 'weekly'){
    
                    for ($x=0; $x < 24; $x++) { 
                        $count_user = DB::connection('mysql_radius')->table('radacct')
                                        ->whereRaw("WEEK(acctstarttime) = $week")
                                        ->whereRaw("HOUR(acctstarttime) = $x")
                                        ->count();
                        
                        $array[$x] += $count_user;
                    }
    
            } elseif($range == 'monthly'){
    
                    for ($x=0; $x < 24; $x++) { 
                        $count_user = DB::connection('mysql_radius')->table('radacct')
                                        ->whereMonth('acctstarttime', $month)
                                        ->whereRaw("HOUR(acctstarttime) = $x")
                                        ->count();
                        
                        $array[$x] += $count_user;
                    }
                
    
            } elseif($range == 'yearly'){
    
                for ($x=0; $x < 24; $x++) { 
                    $count_user = DB::connection('mysql_radius')->table('radacct')
                                    ->whereYear('acctstarttime', $year)
                                    ->whereRaw("HOUR(acctstarttime) = $x")
                                    ->count();
                    
                    $array[$x] += $count_user;
                }
    
            }
    
            return response()->json([
                'status' => 200,
                'data' => $array,
            ]);

        } catch (\Exception $e) {
            dd($e);
        }
        


    }

}
