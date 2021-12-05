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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function listPengumuman(Request $request)
    {
        $list_pengumuman = DB::connection('mysql')->table('tb_pengumuman')
                            ->select('tb_pengumuman.*', 'users.nama')
                            ->join('users','users.id', '=', 'tb_pengumuman.id_users')
                            ->get();

        return view('admin.pengumuman.listPengumuman', ['pengumuman' => $list_pengumuman]);
    }

    public function createPengumuman(Request $request)
    {
        return view('admin.pengumuman.createPengumuman');
    }

    public function postPengumuman(Request $request)
    {
        // $data = $request->validate([
        //     'title' => ['required'],
        //     'desc' => ['required'],
        //     'files' => ['array', 'min:1'],
        //     'files.*' => ['image', 'max:3072'],
        // ]);

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'files' => 'array|min:1',
            'files.*' => 'image|max:3072',
        ]);

        if($validator->fails()){
            dd($validator->failed());
        }


        DB::connection('mysql')->beginTransaction();
        $paths = [];

        try {

            if($request->hasFile('files')){
                foreach($request->file('files') as $file){
                    $path = Storage::disk('public')->putFile('pengumuman', $file);
                    array_push($paths, $path);
                }
            }

            $id_pengumuman = DB::connection('mysql')->table('tb_pengumuman')
                                ->insertGetId([
                                    'id_users' => Auth::id(),
                                    'title' => $request->title,
                                    'desc' => $request->desc,
                                    'status' => 1,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ]);
            
            foreach($paths as $p){

                $det_pengumuman = DB::connection('mysql')->table('tb_det_pengumuman')
                                    ->insert([
                                        'id_pengumuman' => $id_pengumuman,
                                        'link' => $p,
                                        'status' => 1,
                                        'created_at' => date('Y-m-d H:i:s'),
                                    ]);

            }

            DB::connection('mysql')->commit();
            return redirect(route('admin.pengumuman'))->with('success', 'Pengumuman Berhasil dibuat!');

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollback();
            dd($th);
        }

    }

    public function editPengumuman(Request $request)
    {
        $pengumuman = DB::connection('mysql')->table('tb_pengumuman')
                        ->where('id', $request->id)
                        ->first();

        $files = DB::connection('mysql')->table('tb_det_pengumuman')
                    ->where('id_pengumuman', $pengumuman->id)
                    ->where('status', 1)
                    ->get();
        
        return view('admin.pengumuman.editPengumuman', ['pengumuman' => $pengumuman, 'files' => $files]);
    }

    public function updatePengumuman(Request $request)
    {
        // $data = $request->validate([
        //     'title' => ['required'],
        //     'desc' => ['required'],
        //     'files.*' => ['array', 'image'],
        // ]);

        
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'desc' => 'required',
            'files' => 'array|min:1',
            'files.*' => 'image|max:3072',
        ]);

        if($validator->fails()){
            dd($validator->failed());
        }


        DB::connection('mysql')->beginTransaction();

        try {
            
            if($request->hasFile('files')){

                $paths = [];
                

                foreach($request->file('files') as $file){
                    $path = Storage::disk('public')->putFile('pengumuman', $file);
                    array_push($paths, $path);
                }
    
                DB::connection('mysql')->table('tb_pengumuman')
                    ->where('id', $request->id)
                    ->update([
                        'title' => $request->title,
                        'desc' => $request->desc,
                    ]);
                
                foreach($paths as $p){
    
                    $det_pengumuman = DB::connection('mysql')->table('tb_det_pengumuman')
                                        ->insert([
                                            'id_pengumuman' => $request->id,
                                            'link' => $p,
                                            'status' => 1,
                                            'created_at' => date('Y-m-d H:i:s'),
                                        ]);
    
                } 
    
            } else {
    
                DB::connection('mysql')->table('tb_pengumuman')
                    ->where('id', $request->id)
                    ->update([
                        'title' => $request->title,
                        'desc' => $request->desc,
                    ]);

            }

            DB::connection('mysql')->commit();
            return redirect(route('admin.pengumuman'))->with('success', 'Pengumuman Berhasil diubah!');

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollback();
            dd($th);
        }

    }

    public function disablePengumuman(Request $request)
    {
        DB::connection('mysql')->table('tb_pengumuman')
            ->where('id', $request->id)
            ->update(['status' => 0]);

        return redirect(route('admin.pengumuman')->with('success', 'Pengumuman Berhasil di nonaktifkan!'));
    }

    public function disableFile(Request $request)
    {
        DB::connection('mysql')->table('tb_det_pengumuman')
            ->where('id', $request->id)
            ->update(['status' => 0]);

        return redirect()->back()->with('success', 'Gambar Berhasil di nonaktifkan!');

    }

    public function enablePengumuman(Request $request)
    {
        DB::connection('mysql')->table('tb_pengumuman')
            ->where('id', $request->id)
            ->update(['status' => 1]);

        return redirect(route('admin.pengumuman')->with('success', 'Pengumuman Berhasil di aktifkan!'));
    }

    public function enableFile(Request $request)
    {
        DB::connection('mysql')->table('tb_det_pengumuman')
            ->where('id', $request->id)
            ->update(['status' => 1]);
        return redirect()->back()->with('success', 'Gambar Berhasil di aktifkan!');
    }


    //list hotspot user
    public function hotspotUser(Request $request, $user)
    {
        if($user == 'organik'){

            // $Users = DB::connection('mysql')->table('tb_user_hotspot')
            //          ->join('tb_nik', 'tb_user_hotspot.nik_id', '=', 'tb_nik.id')
            //          ->get();

            $users = DB::connection('mysql')->table('tb_user_hotspot')
                    ->select('tb_user_hotspot.*','tb_kategori_user.group', 'tb_nik.nama as nama', 'tb_nik.alamat as alamat')
                    ->join('tb_nik', 'tb_user_hotspot.nik_id', '=', 'tb_nik.id')
                    ->join('tb_det_kategori_user', 'tb_user_hotspot.id', '=', 'tb_det_kategori_user.id_user_hotspot')
                    ->join('tb_kategori_user','tb_det_kategori_user.id_kategori_user', '=', 'tb_kategori_user.id')
                    ->get();
            
            return view('admin.hotspotUser.userRadius', ['users' => $users]);

        } else {

            $user_social = DB::connection('mysql')->table('tb_user_social')
                            ->select('tb_user_social.*','tb_kategori_user.group')
                            ->join('tb_det_kategori_user', 'tb_user_social.id', '=', 'tb_det_kategori_user.id_user_social')
                            ->join('tb_kategori_user','tb_det_kategori_user.id_kategori_user', '=', 'tb_kategori_user.id')
                            ->where('tb_user_social.platform', $user)
                            ->get();

            return view('admin.hotspotUser.userRadius2', ['users' => $user_social]);
        }
    }

    //edit User
    public function editUser(Request $request)
    {
        // $tabel = $request->user == 'organik' ? 'tb_user_hotspot' : 'tb_user_social';

        if($request->user == 'organik'){

            $user = DB::connection('mysql')->table('tb_user_hotspot')
                    ->select('tb_user_hotspot.id as user_id','tb_user_hotspot.username','tb_user_hotspot.kategori','tb_kategori_user.id as group_id','tb_kategori_user.group','tb_nik.nama', 'tb_nik.alamat')
                    ->join('tb_nik', 'tb_user_hotspot.nik_id', '=', 'tb_nik.id')
                    ->join('tb_det_kategori_user', 'tb_user_hotspot.id', '=', 'tb_det_kategori_user.id_user_hotspot')
                    ->join('tb_kategori_user','tb_det_kategori_user.id_kategori_user', '=', 'tb_kategori_user.id')
                    ->where('tb_user_hotspot.id', $request->id)
                    ->first();

            $kategori_user_all = DB::connection('mysql')->table('tb_kategori_user')->get();

            $custom_rules = DB::connection('mysql')->table('tb_custom_rule')
                            ->select('attribute', 'value')
                            ->where('id_user_hotspot', $request->id)
                            ->get();
        } else {

            $user = DB::connection('mysql')->table('tb_user_social')
                    ->select('tb_user_social.id as user_id','tb_user_social.username','tb_user_social.nama','tb_user_social.platform as kategori','tb_kategori_user.id as group_id', 'tb_kategori_user.group')
                    ->join('tb_det_kategori_user', 'tb_user_social.id', '=', 'tb_det_kategori_user.id_user_social')
                    ->join('tb_kategori_user','tb_det_kategori_user.id_kategori_user', '=', 'tb_kategori_user.id')
                    ->where('tb_user_social.id', $request->id)
                    ->first();

            $kategori_user_all = DB::connection('mysql')->table('tb_kategori_user')->get();

            $custom_rules = DB::connection('mysql')->table('tb_custom_rule')
                            ->select('attribute', 'value')
                            ->where('id_user_social', $request->id)
                            ->get();
        }

       return view('admin.hotspotUser.editUser', ['user' => $user, 'groups' => $kategori_user_all, 'custom_rules' => $custom_rules, 'platform' => $request->user]);
    }

    public function updateUser(Request $request)
    {   

        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();

        try {

            DB::connection('mysql')->table('tb_det_kategori_user')
                ->where('id_user_hotspot', $request->user_id)
                ->update([
                    'id_kategori_user' => $request->group,
                ]);
            
            $user = DB::connection('mysql')->table('tb_user_hotspot')
                        ->where('id', $request->user_id)
                        ->first();

            $kategori_user = DB::connection('mysql')->table('tb_kategori_user')
                        ->where('id', $request->group)
                        ->first();
            
            DB::connection('mysql_radius')->table('radusergroup')
                ->where('username', $user->username)
                ->update([
                    'groupname' => $kategori_user->group,
                ]);

            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();

            $request->session()->flash('success', 'Akun Berhasil di Update!');
            return redirect(route('admin.user',['user' => $request->platform]));

        } catch (\Throwable $th) {
            //throw $th;
            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();

            dd($th);
        }
    }

    public function addCustomRules(Request $request)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();

        try {

            $table = $request->platform == 'organik' ? 'tb_user_hotspot' : 'tb_user_social';

            $user = DB::connection('mysql')->table($table)->where('id', $request->user_id)->first();

            $id_rule = DB::connection('mysql')->table('tb_custom_rule')
                ->insertGetId([
                    'id_user_social' => $request->platform != 'organik' ? $request->user_id : null,
                    'id_user_hotspot' => $request->platform == 'organik' ? $request->user_id : null,
                    'nama_custom_rule' => $request->attribute,
                    'value_custom_rule' => $request->value,
                    'status' => 1,
                ]);

            if($request->attribute == 'quota'){

                //cek quota apa lebih dari 3 GB
                $gb = 1073741824;

                if($request->value > 3){

                    $x = floor($request->value/4);
                    $y = $request->value % 4;

                    if($y > 0){
                        $attributes = [
                            ['id_custom_rule' => $id_rule, 'attribute' => 'Mikrotik-Recv-Limit-Gigawords', 'op' => ':=', 'value' => $x],
                            ['id_custom_rule' => $id_rule, 'attribute' => 'Mikrotik-Recv-Limit', 'op' => ':=', 'value' => ($y*$gb)]
                        ];

                        $attributes_radius = [
                            ['username' => $user->username, 'attribute' => 'Mikrotik-Recv-Limit-Gigawords', 'op' => ':=', 'value' => $x],
                            ['username' => $user->username, 'attribute' => 'Mikrotik-Recv-Limit', 'op' => ':=', 'value' => ($y*$gb)]
                        ];

                    } else {
                        $attributes = ['id_custom_rule' => $id_rule, 'attribute' => 'Mikrotik-Recv-Limit-Gigawords', 'op' => ':=', 'value' => $x];

                        $attributes_radius = ['username' => $user->username, 'attribute' => 'Mikrotik-Recv-Limit-Gigawords', 'op' => ':=', 'value' => $x];
                    }
                    
                } else {
                    // kalikan langsung dengan 1073741824
                    $attributes = ['id_custom_rule' => $id_rule, 'attribute' => 'Mikrotik-Recv-Limit', 'op' => ':=', 'value' => ($y*$gb)];

                    $attributes_radius = ['username' => $user->username, 'attribute' => 'Mikrotik-Recv-Limit', 'op' => ':=', 'value' => ($y*$gb)];
                }

            }
            
            DB::connection('mysql')->table('tb_det_custom_rule')
                ->insert($attributes);
                

            DB::connection('mysql_radius')->table('radreply')
                ->insert($attributes_radius);

            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();

            return redirect(route('admin.user.edit',['user' => $request->platform, 'id' => $request->user_id]));
            
        } catch (\Throwable $th) {
            //throw $th;
            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();
            
            dd($th);
        }
        
    }

    public function deleteCustomRules(Request $request)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();

        try {
            
            DB::connection('mysql')->table('tb_custom_rule')
                ->where($request->id)
                ->update(['status' => 0]);
            
            $user = DB::connection('mysql')->table('tb_custom_rule')
                        ->select('tb_user_hotspot.username')
                        ->join('tb_user_hotspot', 'tb_custom_rule.id_user_hotspot','=','tb_user_hotspot.id')
                        ->where('tb_custom_rule.id', $request->id)
                        ->first();

            // Delete attribute on RADIUS DB
            DB::connection('mysql_radius')->table('radreply')
                ->where('username', $user->username)
                ->delete();
                        
            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function enableCustomRules(Request $request)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();

        try {
            DB::connection('mysql')->table('tb_custom_rule')
            ->where($request->id)
            ->update(['status' => 1]);

            $user = DB::connection('mysql')->table('tb_custom_rule')
                ->select('tb_user_hotspot.username')
                ->join('tb_user_hotspot', 'tb_custom_rule.id_user_hotspot','=','tb_user_hotspot.id')
                ->where('tb_custom_rule.id', $request->id)
                ->first();

            $rules = DB::connection('mysql')->table('tb_det_custom_rule')
                ->select('attribute', 'op', 'value')
                ->where('id_custom_rule', $request->id)
                ->get();

            foreach($rules as $rule){
                DB::connection('mysql_radius')->table('radreply')
                    ->insert([
                        'username' => $user->username,
                        'attribute' => $rule->attribute,
                        'op' => ':=',
                        'value' => $rule->value,
                    ]);
            }

            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();
            
        } catch (\Throwable $th) {
            //throw $th;
            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();

            dd($th);
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





    // REPORT
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
                                            // ->whereBetween('period_start',[$senin,$minggu])
                                            ->whereDate('period_start', '>=', $senin)
                                            ->whereDate('period_start', '<=', $minggu)
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
                        // ->whereBetween('period_start',[$senin,$minggu])
                        ->whereDate('period_start', '>=', $senin)
                        ->whereDate('period_start', '<=', $minggu)
                        ->groupBy('username')
                        ->orderBy('GB_total', 'desc')->get();

                $i = 0;
                foreach ($users_radius as $user) {

                    $i++;
                    
                    $detail = DB::connection('mysql')->table('tb_user_hotspot')
                                // ->join('tb_average_speed','tb_user_hotspot.username', '=', 'tb_average_speed.username')
                                // ->select('tb_average_speed.download_speed', 'tb_average_speed.upload_speed', 'tb_user_hotspot.username', 'tb_user_hotspot.kategori')
                                ->where('username',$user->username)->first();

                    if(is_null($detail)){

                        $detail = DB::connection('mysql')->table('tb_user_social')
                                    ->where('username',$user->username)->first();
                        
                        $platform = $detail->platform;
                        $kategori = '-';

                    } else {

                        $platform = "Masyarakat Desa";
                        $kategori = $detail->kategori ;

                    }

                    $speed = DB::connection('mysql')->table('tb_average_speed')
                                ->select(DB::raw('(SUM(download_speed) / SUM(count)) as download_speed, (SUM(upload_speed) / SUM(count)) as upload_speed'))
                                ->where('username',$user->username)
                                ->whereBetween('created_at',[$senin,$minggu])
                                // ->groupBy('username')
                                ->get();

                    // dd($speed);
                    
                    $det_user = [
                        'no' => $i,
                        'username' => $user->username,
                        'kategori' => $kategori,
                        'platform' => $platform,
                        'average_speed' => empty($speed[0]->download_speed) ? 'No Data' : number_format(floatval($speed[0]->upload_speed /1000/1000) , 2 ,'.' , '') . 'Mb / ' . number_format(floatval($speed[0]->download_speed /1000/1000) , 2 ,'.' , '') . 'Mb',
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

                    $speed = DB::connection('mysql')->table('tb_average_speed')
                            ->select(DB::raw('(SUM(download_speed) / SUM(count)) as download_speed, (SUM(upload_speed) / SUM(count)) as upload_speed'))
                            ->where('username',$user->username)
                            ->whereMonth('created_at', $month)
                            ->groupBy('username')
                            ->get();

                    
                    $det_user = [
                        'no' => $i,
                        'username' => $user->username,
                        'kategori' => $kategori,
                        'platform' => $platform,
                        'average_speed' => empty($speed[0]->download_speed) ? 'No Data' : number_format(floatval($speed[0]->upload_speed /1000/1000) , 2 ,'.' , '') . 'Mb / ' . number_format(floatval($speed[0]->download_speed /1000/1000) , 2 ,'.' , '') . 'Mb',
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

                    $speed = DB::connection('mysql')->table('tb_average_speed')
                            ->select(DB::raw('(SUM(download_speed) / SUM(count)) as download_speed, (SUM(upload_speed) / SUM(count)) as upload_speed'))
                            ->where('username',$user->username)
                            ->whereYear('created_at', $year)
                            ->groupBy('username')
                            ->get();

                    
                    $det_user = [
                        'no' => $i,
                        'username' => $user->username,
                        'kategori' => $kategori,
                        'platform' => $platform,
                        'average_speed' => empty($speed[0]->download_speed) ? 'No Data' : number_format(floatval($speed[0]->upload_speed /1000/1000) , 2 ,'.' , '') . 'Mb / ' . number_format(floatval($speed[0]->download_speed /1000/1000) , 2 ,'.' , '') . 'Mb',
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
