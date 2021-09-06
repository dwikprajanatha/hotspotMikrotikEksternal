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
        if(in_array($range, ['daily', 'weekly', 'monthly', 'yearly'])){
            
            return view('admin.report.bandwidthReport');

        } else {

            abort(404);

        }
        
    }


    // ALL API for report start here
    public function apiDataPertumbuhanPengguna($range)
    {
        try {
            
            if($range == 'weekly'){

                $date = new DateTime(date('Y-m-d', strtotime("last week monday")));
                // $end = date('Y-m-d', strtotime("last week sunday"));

                // dd($date);
                $array = [];

                for ($i=0; $i < 7; $i++) {

                    // array_push($array,[$date->format('Y-m-d')]);
                    $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                                            ->whereDate('created_at',$date->format('Y-m-d'))->count();

                    $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                                            ->whereDate('created_at',$date->format('Y-m-d'))->count();

                    // $date = date('Y-m-d', strtotime())
                    array_push($array, [$userOrganikCount + $userSocialCount]);

                    $date->modify('+1 day');

                }

            } elseif($range == 'monthly'){
                
                $date = new DateTime;


                //get first day of month
                $weekOfMonth = date('W', strtotime('first day of this month'));

                // dd($weekOfMonth);

                $array = [];

                for ($i=0; $i < 4; $i++) { 
                    $date->setISODate(date('Y'), $weekOfMonth);
                    $senin = $date->format('Y-m-d');
                    // print($senin->format('Y-m-d'));

                    $date->modify("sunday this week");
                    $minggu = $date->format('Y-m-d');
                    
                    $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                                            ->whereBetween('created_at',[$senin,$minggu])->count();

                    $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                                            ->whereBetween('created_at',[$senin,$minggu])->count();



                    // print($senin->format('Y-m-d'));
                    // dd();

                    array_push($array,[$userOrganikCount + $userSocialCount]);

                    $weekOfMonth++;

                }
                
                // dd($array);

            } elseif($range == 'yearly'){

                $array = [];
                
                for ($i=1; $i <= 12 ; $i++) {

                    $userOrganikCount = DB::connection('mysql')->table('tb_user_hotspot')
                                            ->whereMonth('created_at',$i)->count();

                    $userSocialCount = DB::connection('mysql')->table('tb_user_social')
                                            ->whereMonth('created_at',$i)->count();

                    array_push($array,[$userOrganikCount + $userSocialCount]);
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


    public function FunctionName(Type $var = null)
    {
        # code...
    }


}
