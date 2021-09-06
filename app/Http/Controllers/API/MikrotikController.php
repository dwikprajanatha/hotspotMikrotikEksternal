<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

//Mikrotik RouterOS
use RouterOS;
use RouterOS\Query;

class MikrotikController extends Controller
{
    public function connectRouterOS()
    {
        try {

            $host = '192.168.1.199';
            $user = 'admin';
            $password = '';
            
            $client = new RouterOS\Client([
                'host' => $host,
                'user' => $user,
                'pass' => $password,
                'port' => 8728,
            ]);

            // $client = RouterOS::client($config);

            return $client;
            
        } catch (\Exception $e) {
            dd("Goblok");
        }
    }

    public function formatBytes($bytes)
    {   
        return(round(($bytes/1000000),1)) . "kbps";
    }


    //API Check

    public function checkBandwidth(Request $request)
    {
        try {

            $client = $this->connectRouterOS();

            $query = (new Query('/interface/print'))
                        ->where('name', 'ether1');

            $response = $client->query($query)->read();
            
            $bandwidth = $this->formatBytes($response[0]['tx-byte']);

            dd($bandwidth);
            
            // dd($this->formatBytes($response[0]['tx-byte']), $response[0]);

        } catch (\Exception $th) {
            //throw $th;
            dd($th);
        }
    }

    public function checkHealth(Request $request)
    {
        try {
            $client = $this->connectRouterOS();

            $query = (new Query('/system/resource/print'));

            $response = $client->query($query)->read();

            dd($response);

        } catch (\Exception $th) {
            dd($th);
        }
    }

    public function checkActiveUserHotspot(Type $var = null)
    {
        try {

            $client = $this->connectRouterOS();

            $query = (new Query('/ip/hotspot/active/print'));
                    // ->where('count-only','true');

            $result = $client->query($query)->read();

            dd(count($result));

        } catch (\Exception $th) {
            dd($result);
        }
    }






    
    //SHOW 
    public function showQueue(Request $request)
    {
        try {
            $client = $this->connectRouterOS();

            $query = (new Query('/queue/simple/print'));

            $response = $client->query($query)->read();

            dd($response);

        } catch (\Exception $th) {
            dd($th);
        }
    }


 


















    //Mulai Keperluan Admin

    public function showUserHotspot(Request $request)
    {
        try {
            $client = $this->connectRouterOS();
            
            // to Mikrotik
            $getActiveUser = (new Query('ip/hotspot/active/print'));
            $getUserMikrotik = (new Query('ip/hotspot/user/print'));
            
            $activeUsers = $client->query($getActiveUser)->read();
            $usersMikrotik = $client->query($getUserMikrotik)->read();

            // to RADIUS
            // $radiusUser = DB::connection('mysql_radius')->table('radcheck')
            //                 ->join('radgroup','radcheck.username','=','radcheck.username')
            //                 ->get();
            
            //to MYSQL Local
            // $localUser = DB::connection('mysql')->table('tb_user_hotspot')
            //                 ->join('nik','tb_user_hotspot.nik_id','=','nik.id')
            //                 ->get();

            // $sosmedUser = DB::connection('mysql')->table('tb_user_social')
            //                 ->get();

            $response = [
                'activeUsers' => $activeUsers,
                'usersMikrotik' => $usersMikrotik,
            ];

            dd($response);

        } catch (\Exception $th) {
            dd($th);
        }
    }

    public function show(Type $var = null)
    {
        # code...
    }

}
