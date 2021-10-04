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

            $host = '192.168.40.138';
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

            return json_encode([
                'status' => 200,
                'data' => [
                    'bandwidth' => $bandwidth,
                ]
            ]);

            // dd($bandwidth);
            
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

            // dd($response);
            return json_encode([
                'status' => 200,
                'data' => $response[0],
            ]);

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

            return json_encode([
                'status' => 200,
                'data' => [
                    'users' => $result,
                ],
            ]);
            
            // var_dump($result);
            // dd(count($result));

        } catch (\Exception $th) {
            dd($th);
        }
    }



    /**
     * QUEUE
     */



    // Show Queue 
    public function showQueue(Request $request)
    {
        try {
            $client = $this->connectRouterOS();

            $query = (new Query('/queue/simple/print'));

            $response = $client->query($query)->read();

            return json_encode([
                'status' => 200,
                'data' => $response,
            ]);

            // dd($response);

        } catch (\Exception $th) {
            dd($th);
        }
    }


    // Add Queue
    public function addQueue(Request $request)
    {
        try {

            $client = $this->connectRouterOS();

            $maxDownUp =  $request->maxUp .'/'. $request->maxDown;
            $limitDownUp =  $request->limitUp.'/'.$request->limitDown;
            
            $query = (new Query('queue/simple/add'))
                            ->equal('name', $request->name)
                            ->equal('target', $request->target)
                            ->equal('max-limit', $maxDownUp)
                            ->equal('limit-at', $limitDownUp)
                            ->equl('parent', isset($request->parent) ? $request->parent : 'none')
                            ->equal('disabled', 'no');
                
            $response = $client->query($query)->read();

            dd($response);


        } catch (\Exception $th) {
            dd($th);
        }
    }

    // Update Queue
    public function updateQueue(Request $request)
    {
        try {
            
            $client = $this->connectRouterOS();

            $maxDownUp =  $request->maxUp .'/'. $request->maxDown;
            $limitDownUp =  $request->limitUp.'/'.$request->limitDown;

            $query = (new query('queue/simple/set'))
                         ->equal('name', $request->name)
                            ->equal('target', $request->target)
                            ->equal('max-limit', $maxDownUp)
                            ->equal('limit-at', $limitDownUp)
                            ->equl('parent', isset($request->parent) ? $request->parent : 'none')
                            ->equal('disabled', $request->status)
                            ->tag($request->number);

            $response = $client->query($query)->read();

        } catch (\Exception $th) {
            dd($th);
        }
    }

    // Disable Queue
    public function disableQueue(Request $request)
    {
        try {
            
            $client = $this->connectRouterOS();

            $maxDownUp =  $request->maxUp .'/'. $request->maxDown;
            $limitDownUp =  $request->limitUp.'/'.$request->limitDown;

            $query = (new query('queue/simple/set'))
                            ->equal('disabled', yes)
                            ->tag($request->number);

            $response = $client->query($query)->read();

        } catch (\Exception $th) {
            dd($th);
        }
    }


    /**
     * END QUEUE
     */


    /**
     * HOTSPOT
     */
    public function addScheduleHotspot(Request $request)
    {
        try {
            
            $client = $this->connectRouterOS();

            $startDate = date('M/d/Y');

            $query1 = (new query('system/schedule/add'))
                        ->equal('name', $request->name)
                        ->equal('start-date', $startDate)
                        ->equal('start-time', $startTime)
                        ->equal('interval', '1d')
                        ->equal('on-event','turn_on_hotspot');

            $query2 = (new query('system/schedule/add'))
                        ->equal('name', $request->name)
                        ->equal('start-date', $startDate)
                        ->equal('start-time', $endTime)
                        ->equal('interval', '1d')
                        ->equal('on-event','turn_on_hotspot');

            $response1 = $client->query($query1)->read();
            $response2 = $client->query($query2)->read();

        } catch (\Exception $th) {
            dd($th);
        }
    }

    public function editScheduleHotspot(Request $request)
    {
        try {
            
            $client = $this->connectRouterOS();

            $startDate = date('M/d/Y');

            $query1 = (new query('system/schedule/add'))
                        ->equal('name', $request->name)
                        ->equal('start-date', $startDate)
                        ->equal('start-time', $startTime)
                        ->equal('interval', '1d')
                        ->equal('on-event','turn_on_hotspot')
                        ->tag($request->number);

            $query2 = (new query('system/schedule/add'))
                        ->equal('name', $request->name)
                        ->equal('start-date', $startDate)
                        ->equal('start-time', $endTime)
                        ->equal('interval', '1d')
                        ->equal('on-event','turn_off_hotspot')
                        ->tag($request->number);

            $response1 = $client->query($query1)->read();
            $response2 = $client->query($query2)->read();

        } catch (\Exception $th) {
            dd($th);
        }
    }

    /**
     * END HOTSPOT
     */










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

            return json_encode([
                'status' => 200,
                'data' => $response,
            ]);

            

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
