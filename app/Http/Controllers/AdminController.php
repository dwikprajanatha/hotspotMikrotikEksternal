<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;
use Illuminate\Support\Str;
use DateTime;

class AdminController extends Controller
{

    public function showFormLogin()
    {
        return view('admin.login.loginAdmin');
    }

    public function loginAdmin(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required', 'min:8'],
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();

            DB::connection('mysql')->table('users')
                ->where('id', Auth::id())
                ->update(['api_token' => Str::random(60)]);

            return redirect()->intended(route('admin.dashboard'));

        } else {
            return back()->withErrors([
                'email' => 'Email dan Password tidak cocok',
            ]);
        }
    }

    public function dashboard(Request $request)
    {
        return view('admin.dashboard.dashboardAdmin');
    }

    public function HotspotUser(Request $request, $user)
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
            
            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                $array_label = [];

                for ($i=0; $i < 7; $i++) {

                    // array_push($array,[$date->format('Y-m-d')]);
                    // $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                    //                         ->whereDate('created_at',$date->format('Y-m-d'))->count();

                    // $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                    //                         ->whereDate('created_at',$date->format('Y-m-d'))->count();

                    // $date = date('Y-m-d', strtotime())
                    // array_push($array, [ "data" => $userOrganikCount + $userSocialCount, "label" => $date->format('Y-m-d')]);
                    
                    array_push($array, $date->format('Y-m-d'));

                    $date->modify('+1 day');

                }

            } elseif($range == 'monthly'){
                
                $date = new DateTime;

                $array = [];

                for ($i=0; $i < 4; $i++) { 
                    $date->setISODate($year, $week);
                    $senin = $date->format('Y-m-d');

                    $date->modify("sunday this week");
                    $minggu = $date->format('Y-m-d');
                    
                    // $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                    //                         ->whereBetween('created_at',[$senin,$minggu])->count();

                    // $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                    //                         ->whereBetween('created_at',[$senin,$minggu])->count();

                    // array_push($array, [ "data" => $userOrganikCount + $userSocialCount, "label" => $date->format('W')]);
                    
                    array_push($array, "awal : ". $senin . " | " . "akhir : ". $minggu);

                    $week++;

                }

            } elseif($range == 'yearly'){

                $date = new DateTime;
                $date->setISODate($year, $week);

                $array = [];
               
                for ($i=1; $i <= 12 ; $i++) {

                    $month = $date->format('n');
                    
                    // $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                    //                         ->whereMonth('created_at',$i)->whereYear('created_at', $year)->count();

                    // $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                    //                         ->whereMonth('created_at',$i)->whereYear('created_at', $year)->count();

                    array_push($array, [ "data" => $userOrganikCount + $userSocialCount, "label" => $date->format('F')]);

                    array_push($array, "bulan ke : ". $month);
                    
                    $date->modify('+1 Month');

                }
                

            }


            return response()->json([
                'status' => 200,
                'data' => $array,
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

            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                $array = [];

                for ($i=0; $i < 7; $i++) {

                    $penggunaanTotal = DB::connection('mysql_radius')->table('data_usage_by_period')
                                            ->select(DB::raw('(SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000) as GB_total'))
                                            ->whereNotNull('period_end')
                                            ->whereDay('period_start', $date->format('Y-m-d'))
                                            ->groupBy(DB::raw('DAY(period_start)'))->get();
                    
                    array_push($array, ['data' => $penggunaanTotal[0]['GB_total'], 'label' => $date->format('d-m-Y')]);

                    // array_push($array, $date->format('Y-m-d'));

                    $date->modify('+1 day');
                }

            } elseif($range == 'monthly'){
                
                $date = new DateTime;

                $array = [];

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

                    // array_push($array, "awal : ". $senin . " | " . "akhir : ". $minggu);
                    array_push($array, ['data' => $penggunaanTotal[0]['GB_total'], 'label' => $date->format('W')]);

                    $week++;

                }

            } elseif($range == 'yearly'){

                $date = new DateTime;
                $date->setISODate($year, $week);
                
                $array = [];
                
                for ($i=1; $i <= 12 ; $i++) {
                    
                    $penggunaanTotal = DB::connection('mysql_radius')->table('data_usage_by_period')
                                        ->select(DB::raw('(SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000) as GB_total'))
                                        ->whereNotNull('period_end')
                                        ->whereMonth('period_start',$i)
                                        ->whereYear('period_start', $year)
                                        ->groupBy(DB::raw('MONTH(period_start)'))->get();
                   
                    array_push($array, ['data' => $penggunaanTotal[0]['GB_total'], 'label' => $date->format('F')]);

                    $date->modify('+1 month');
                }
            }


            return response()->json([
                'status' => 200,
                'data' => $array,
            ]);


        } catch (\Execption $th) {
            dd($th);
        }
        
    }

    public function apiProporsiPlatform($range, $tgl)
    {
        try {
            
            $date_now = DateTime::createFromFormat('dmY', $tgl);
            // dd($date);

            $year = $date_now->format('Y');
            $month = $date_now->format('m');
            $week = $date_now->format('W');

            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                $senin = $date->format('Y-m-d');
                
                $date->modify('sunday this week');

                $minggu = $date->format('Y-m-d');

                

                $organik = DB::connection('mysql')->table('tb_user_hotspot')
                            ->whereBetween('created_at',[$senin,$minggu])->count();

                $google = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','google')
                            ->whereBetween('created_at',[$senin,$minggu])->count();

                $facebook = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','facebook')
                            ->whereBetween('created_at',[$senin,$minggu])->count();

                $array = [$organik,$google,$facebook];


            } elseif($range == 'monthly'){

                $organik = DB::connection('mysql')->table('tb_user_hotspot')
                            ->whereMonth('created_at',$month)->count();

                $google = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','google')
                            ->whereMonth('created_at',$month)->count();

                $facebook = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','facebook')
                            ->whereMonth('created_at',$month)->count();

                $array = [$organik,$google,$facebook];

            } elseif($range == 'yearly'){

                $organik = DB::connection('mysql')->table('tb_user_hotspot')
                            ->whereYear('created_at',$year)->count();

                $google = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','google')
                            ->whereYear('created_at',$year)->count();

                $facebook = DB::connection('mysql')->table('tb_user_social')
                            ->where('platform','facebook')
                            ->whereYear('created_at',$year)->count();

                $array = [$organik,$google,$facebook];

            }


            return response()->json([
                'status' => 200,
                'data' => $array,
            ]);


        } catch (\Execption $th) {
            dd($th);
        }
    }

    public function apiProporsiUmur($range, $tgl)
    {
        try {
            
            $date_now = DateTime::createFromFormat('dmY', $tgl);
            // dd($date);

            $year = $date_now->format('Y');
            $month = $date_now->format('m');
            $week = $date_now->format('W');

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


                // $google = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','google')
                //             ->whereBetween('created_at',[$senin,$minggu])->count();

                // $facebook = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','facebook')
                //             ->whereBetween('created_at',[$senin,$minggu])->count();

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

                // $google = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','google')
                //             ->whereMonth('created_at',$month)->count();

                // $facebook = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','facebook')
                //             ->whereMonth('created_at',$month)->count();

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

                // $google = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','google')
                //             ->whereYear('created_at',$year)->count();

                // $facebook = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','facebook')
                //             ->whereYear('created_at',$year)->count();

                // $array = [$organik,$google,$facebook];

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

            if($range == 'weekly'){
            
                $date = new DateTime();
                $date->setISODate($year, $week);

                $senin = $date->format('Y-m-d');
                
                $date->modify('sunday this week');

                $minggu = $date->format('Y-m-d');

                $array = [];


                $users_radius = DB::connection('mysql_radius')->table('data_usage_by_period')
                        ->select('username','acctstarttime AS start_time', DB::raw('(SUM(acctinputoctets)/1000/1000/1000) + (SUM(acctoutputoctets)/1000/1000/1000) AS GB_total'))
                        ->whereNotNull('period_end')
                        ->whereBetween('period_start',[$senin,$minggu])
                        ->orderBy('GB_total', 'desc')->get();

                
                foreach ($users_radius as $user) {
                    
                    $detail = DB::connection('mysql')->table('tb_user_hotspot')
                                ->where('username',$user->username)->first();
                    
                    if(is_null($detail)){

                        $detail = DB::connection('mysql')->table('tb_user_social')
                        ->where('username',$user->username)->first();
            
                    }

                    $det_user = [
                        'username' => $user->username,
                        // 'kategori' => ,
                    ];

                    array_push($array, $detail);
                }

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

                // $google = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','google')
                //             ->whereMonth('created_at',$month)->count();

                // $facebook = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','facebook')
                //             ->whereMonth('created_at',$month)->count();

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

                // $google = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','google')
                //             ->whereYear('created_at',$year)->count();

                // $facebook = DB::connection('mysql')->table('tb_user_social')
                //             ->where('platform','facebook')
                //             ->whereYear('created_at',$year)->count();

                // $array = [$organik,$google,$facebook];

            }


            return response()->json([
                'status' => 200,
                'data' => $array,
            ]);


        } catch (\Execption $th) {
            dd($th);
        }
    }

}
