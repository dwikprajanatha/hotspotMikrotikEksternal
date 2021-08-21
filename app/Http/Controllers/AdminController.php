<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Session;
use Auth;

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

    public function report()
    {
        # code...
    }

}
