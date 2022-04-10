<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Http;
use Session;

//Mikrotik RouterOS
use RouterOS;
use RouterOS\Query;

class MikrotikController extends Controller
{
    public function connectRouterOS()
    {
        try {

            // $host = '192.168.10.1';
            // $user = 'admin';
            // $password = 'dwik1234';
            
            $client = new RouterOS\Client([
                'host' => env('MIKROTIK_HOST'),
                'user' => env('MIKROTIK_USER'),
                'pass' => env('MIKROTIK_PASS'),
                'port' => (int)env('MIKROTIK_PORT'),
            ]);

            // $client = RouterOS::client($config);

            return $client;
            
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function formatBytes($bytes)
    {   
        return(round(($bytes/1000000),1)) . "kbps";
    }


    //API Check
    public function checkBandwidth()
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

    public function checkHealth()
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

    public function checkActiveUserHotspot()
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

    public function getSpeedAllConnectedUser()
    {

        try {

            $client = $this->connectRouterOS();

            $query = (new Query('/queue/simple/print'));
    
            $result = $client->query($query)->read();

            $arr_result = [];

            foreach($result as $res){

                //ambil username
                $a = explode('-', $res['name']);
                $username = preg_replace('/[^a-zA-Z0-9\_\@\.]/', "", $a[1]);
                // $username = substr($a[1], 0, -1);

                if($username != 'hotspot1'){

                    list($upload, $download) = explode("/", $res['rate']);

                    array_push($arr_result, [
                        'username' => $username,
                        'upload_speed' => $upload,
                        'download_speed' => $download,
                    ]);
                    
                }

            }
    
            return json_encode([
                'status' => 200,
                'data' => [
                    'queues' => $arr_result,
                ],
            ]);

        } catch (\Throwable $th) {
            dd($th);
        }

    }

    /**
     * KATEGORI USER
     */

    public function listGroupUser()
    {
        $kategori = DB::connection('mysql')->table('tb_kategori_user')->get();

        return view('admin.mikrotik.kategori_user.listKategoriUser', ['kategori' => $kategori]);
    } 

    public function showCreateGroupUser()
    {
        return view('admin.mikrotik.kategori_user.createKategoriUser');
    }

    public function createGroupUser(Request $request)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();

        try {

            DB::connection('mysql')->table('tb_kategori_user')->insert([
                'group' => $request->group,
                'rx_rate' => $request->rx_rate,
                'tx_rate' => $request->tx_rate,
                'min_rx_rate' => $request->min_rx_rate,
                'min_tx_rate' => $request->min_tx_rate,
                'priority' => $request->priority,
                'idle_timeout' => $request->idle_timeout,
                'session_timeout' => $request->session_timeout,
                'port_limit' => $request->port_limit,
            ]);

            // rx-rate[/tx-rate] [rx-burst-rate[/tx-burst-rate] [rx-burst-threshold[/tx-burst-threshold] [rx-burst-time[/tx-burst-time] [priority] [rx-rate-min[/tx-rate-min]]]]
            $rate = $request->rx_rate . '/' . $request->tx_rate . ' 0/0 0/0 0/0 ' . $request->priority . ' ' . $request->min_rx_rate . '/' .  $request->min_tx_rate;

            DB::connection('mysql_radius')->table('radgroupreply')->insert([
                ['groupname' => $request->group, 'attribute' => 'Mikrotik-Rate-Limit', 'op' => ':=', 'value' => $rate],
                ['groupname' => $request->group, 'attribute' => 'Idle-Timeout', 'op' => ':=', 'value' => $request->idle_timeout],
                ['groupname' => $request->group, 'attribute' => 'Session-Timeout', 'op' => ':=', 'value' => $request->session_timeout],
                ['groupname' => $request->group, 'attribute' => 'Port-Limit', 'op' => ':=', 'value' => $request->port_limit],
            ]);

            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();

            $request->session()->flash('success', 'Kategori User Berhasil dibuat!');
            return redirect(route('admin.mikrotik.listGroupUser'));

        } catch (\Throwable $th) {

            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();

            dd($th);
        }
    }

    public function editCreateGroupUser(Request $request)
    {
        $kategori = DB::connection('mysql')->table('tb_kategori_user')->where('id', $request->id)->first();

        return view('admin.mikrotik.kategori_user.editKategoriUser', ['kategori' => $kategori]);
    }

    public function updateGroupUser(Request $request)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();
        
        try {

            $group_lama = DB::connection('mysql')->table('tb_kategori_user')->select('group')->where('id', $request->id)->first();
            
            DB::connection('mysql')->table('tb_kategori_user')
            ->where('id', $request->id)
            ->update([
                'group' => $request->group,
                'rx_rate' => $request->rx_rate,
                'tx_rate' => $request->tx_rate,
                'min_rx_rate' => $request->min_rx_rate,
                'min_tx_rate' => $request->min_tx_rate,
                'priority' => $request->priority,
                'idle_timeout' => $request->idle_timeout,
                'session_timeout' => $request->session_timeout,
                'port_limit' => $request->port_limit,
            ]);

            // rx-rate[/tx-rate] [rx-burst-rate[/tx-burst-rate] [rx-burst-threshold[/tx-burst-threshold] [rx-burst-time[/tx-burst-time] [priority] [rx-rate-min[/tx-rate-min]]]]
            $rate = $request->rx_rate . '/' . $request->tx_rate . ' 0/0 0/0 0/0 ' . $request->priority . ' ' . $request->min_rx_rate . '/' .  $request->min_tx_rate;


            DB::connection('mysql_radius')->table('radgroupreply')
                ->where('groupname', $group_lama->group)
                ->where('attribute', 'Mikrotik-Rate-Limit')
                ->update([
                    'groupname' => $request->group, 
                    'value' => $rate,
                ]);

            DB::connection('mysql_radius')->table('radgroupreply')
                ->where('groupname', $group_lama->group)
                ->where('attribute', 'Idle-Timeout')
                ->update([
                    'groupname' => $request->group, 
                    'value' => $request->idle_timeout,
                ]);

            DB::connection('mysql_radius')->table('radgroupreply')
                ->where('groupname', $group_lama->group)
                ->where('attribute', 'Session-Timeout')
                ->update([
                    'groupname' => $request->group, 
                    'value' => $request->session_timeout,
                ]);

            DB::connection('mysql_radius')->table('radgroupreply')
                ->where('groupname', $group_lama->group)
                ->where('attribute', 'Port-Limit')
                ->update([
                    'groupname' => $request->group, 
                    'value' => $request->port_limit,
                ]);

            DB::connection('mysql_radius')->table('radusergroup')
                ->where('groupname', $group_lama->group)
                ->update(['groupname' => $request->group]);

            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();

            $request->session()->flash('success', 'Kategori User Berhasil di Update!');
            return redirect(route('admin.mikrotik.listGroupUser'));

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();

            dd($th);
        }
    }

    public function deleteKategoriUser(Request $request)
    {
        DB::connection('mysql')->beginTransaction();
        DB::connection('mysql_radius')->beginTransaction();

        try {

            $group_name = DB::connection('mysql')->table('tb_kategori_user')->select('group')->where('id', $request->id)->first();

            DB::connection('mysql')->table('tb_kategori_user')
                ->where('id', $request->id)
                ->update(['status' => 0]);

            DB::connection('mysql_radius')->table('radgroupreply')
                ->where('groupname', $group_name->group)->delete();

            DB::connection('mysql_radius')->table('radusergroup')
                ->where('groupname', $group_name->group)->delete();

            DB::connection('mysql')->commit();
            DB::connection('mysql_radius')->commit();

            $request->session()->flash('success', 'Kategori User Berhasil di Disable!');
            return redirect(route('admin.mikrotik.listGroupUser'));

        } catch (\Throwable $th) {
            DB::connection('mysql')->rollback();
            DB::connection('mysql_radius')->rollback();

            dd($th);
        }
    }

    // public function enableKategoriUser(Request $request)
    // {
    //     DB::connection('mysql')->beginTransaction();
    //     DB::connection('mysql_radius')->beginTransaction();

    //     try {
    //         $kategori = DB::connection('mysql')->table('tb_kategori_user')->where('id', $request->id)->first();

    //         DB::connection('mysql')->table('tb_kategori_user')
    //         ->where('id', $request->id)
    //         ->update(['status' => 1]);

    //         $rate = $kategori->rx_rate . '/' . $kategori->tx_rate . ' 0/0 0/0 0/0 ' . $kategori->priority . ' ' . $kategori->min_rx_rate . '/' .  $kategori->min_tx_rate;

    //         DB::connection('mysql_radius')->table('radgroupreply')->insert([
    //             ['groupname' => $kategori->group, 'attribute' => 'Mikrotik-Rate-Limit', 'op' => ':=', 'value' => $rate],
    //             ['groupname' => $kategori->group, 'attribute' => 'Idle-Timeout', 'op' => ':=', 'value' => $kategori->idle_timeout],
    //             ['groupname' => $kategori->group, 'attribute' => 'Session-Timeout', 'op' => ':=', 'value' => $kategori->session_timeout],
    //             ['groupname' => $kategori->group, 'attribute' => 'Port-Limit', 'op' => ':=', 'value' => $kategori->port_limit],
    //         ]);

    //         DB::connection('mysql')->commit();
    //         DB::connection('mysql_radius')->commit();

    //     } catch (\Throwable $th) {
    //         DB::connection('mysql')->rollback();
    //         DB::connection('mysql_radius')->rollback();

    //         dd($th);
    //     }
    // }
    


    /**
     * QUEUE
     */

    // Show Queue 
    public function showQueue()
    {
        try {

            $client = $this->connectRouterOS();

            $query = (new Query('/queue/simple/print'));

            $response = $client->query($query)->read();

            return json_encode([
                'status' => 200,
                'data' => [
                    'queues' => $response,
                ] 
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
            
            $query = (new Query('/queue/simple/add'))
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

            $query = (new query('/queue/simple/set'))
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

            $query = (new query('/queue/simple/set'))
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


    public function showHotspot()
    {
        try {
            
            $client = $this->connectRouterOS();

            $query = (new Query('/ip/hotspot/print'));

            $response = $client->query($query)->read();

            return json_encode([
                'status' => 200,
                'data' => [
                    'hotspot' => $response,
                ] 
            ]);

            
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function addScheduleHotspot(Request $request)
    {
        try {
            
            $client = $this->connectRouterOS();

            $startDate = date('M/d/Y');

            $query1 = (new query('/system/schedule/add'))
                        ->equal('name', $request->name)
                        ->equal('start-date', $startDate)
                        ->equal('start-time', $startTime)
                        ->equal('interval', '1d')
                        ->equal('on-event','turn_on_hotspot');

            $query2 = (new query('/system/schedule/add'))
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

            $query1 = (new query('/system/schedule/add'))
                        ->equal('name', $request->name)
                        ->equal('start-date', $startDate)
                        ->equal('start-time', $startTime)
                        ->equal('interval', '1d')
                        ->equal('on-event','turn_on_hotspot')
                        ->tag($request->number);

            $query2 = (new query('/system/schedule/add'))
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


    /**
     *  START LOAD BALANCING 
     */

    public function listLoadBalancing(Request $request)
    {
        $data = DB::connection('mysql')->table('tb_load_balancing')->get();

        // foreach ($d as $data) {
        //     $detail = DB::connection('mysql')->table('tb_det_load_balancing')->where('id', $d->id)->where('status', 1)->get();
        //     $arr_data = [
        //         'target' => $d->target,
        //         'deskripsi' => $d->deskripsi,
        //     ]    
        // }

        return view('admin.mikrotik.load_balancing.listLoadBalancing', ['loadBalancing' => $data] );
    }

    

    public function showLoadBalancing(Request $request)
    {
        
        $client = $this->connectRouterOS();
        $query = (new Query('/interface/print'));
        
        $list_interface = $client->query($query)->read();

        // dd($list_interface);
        
        return view('admin.mikrotik.load_balancing.createLoadBalancing', ['interfaces' => $list_interface]);
        
    }

    public function createLoadBalancing(Request $request)
    {


        $data = $request->validate([
            'nama' => ['string'],
            'interface' => ['array', 'min:2'],
            'ip_address' => ['array', 'min:2'],
            'ip_address.*' => ['ip'],
            'network' => ['array', 'min:2'],
            'network.*' => ['ip'],
            'gateway' => ['array', 'min:2'],
            'gateway.*' => ['ip'],
            'dns' => ['array', 'min:2'],
            'dns.*' => ['ip'],
            'bandwidth' => ['array'],
            'bandwidth.*' => ['alpha_num'],
        ]);

        $client = $this->connectRouterOS();

        $arr_data = [];

        for($i = 0; $i < sizeof($request->bandwidth); $i++){
            
            $arr_data[$i] = [
                                'nama' => $request->nama, 
                                'interface' => $request->interface[$i], 
                                'ip_address' => $request->ip_address[$i], 
                                'network' => $request->network[$i], 
                                'gateway' => $request->gateway[$i], 
                                'dns' => $request->dns[$i], 
                                'bandwidth' => $request->bandwidth[$i], 
                            ];

        }
        

        //sort array dari bandwidth terkecil ke terbesar
        for($i = 0; $i < sizeof($arr_data); $i++){

            for($i = 0; $i < (sizeof($arr_data) - 1); $i++){

                if($arr_data[$i]['bandwidth'] > $arr_data[$i + 1]['bandwidth']){

                    $arr_data_sementara = [];

                    $arr_data_sementara = $arr_data[$i];
                    $arr_data[$i] = $arr_data[$i + 1];
                    $arr_data[$i + 1] = $arr_data_sementara;

                }
                
            }
            
        }


        foreach($arr_data as $data){

            $new_ip = (new Query('/ip/address/add'))
                        ->equal('interface', $data['interface'])
                        ->equal('network', $data['network'])
                        ->equal('address', $data['ip_address']);

            $response_new_ip = $client->query($new_ip)->read();
            
            
        }
        //add IP on interface




    }
    
    public function editLoadBalancing(Request $request)
    {
        # code...
    }

    public function updateLoadBalancing(Request $request)
    {
        # code...
    }

    public function disableLoadBalancing(Request $request)
    {
        # code...
    }

    
    public function enableLoadBalancing(Request $request)
    {
        # code...
    }

    /**
     *  END LOAD BALANCING
     */








    //Mulai Keperluan Admin

    // Show Active User Hotspot
    public function showUserHotspot(Request $request)
    {
        try {

            $response = $this->checkActiveUserHotspot();

            $response = json_decode($response, true);
            // $hasil = $response->data;
            $users = $response['data']['users'];

            $arr_user = [];

            foreach ($users as $user) {

                $data = DB::connection('mysql')->table('tb_user_hotspot')->join('tb_nik', 'tb_nik.id', '=', 'tb_user_hotspot.nik_id')
                        ->where('username', $user['user'])->first();

                if(is_null($data)){
                    $data = DB::connection('mysql')->table('tb_user_social')->where('username', $user['user'])->first();
                }

                $user_data = [
                    'username' => $data->username,
                    'nama' => $data->nama,
                    'alamat' => isset($data->alamat) ? $data->alamat : '-',
                    'platform' => isset($data->platform) ? $data->platform : 'organik',
                    'uptime' => $user['uptime'],
                ];

                array_push($arr_user, $user_data);
            }

            return view('admin.mikrotik.userHotspot', ['users' => $arr_user]);


        } catch (\Exception $th) {
            dd($th);
        }
    }

    public function getListQueue(Request $request)
    {
        try {

            $response = $this->showQueue();

            $response = json_decode($response,true);

            $queues = $response['data']['queues'];

            return view('admin.mikrotik.queue',['queues' => $queues]);

        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

    public function getHotspot(Request $request)
    {
        try {
            
            $response = $this->showHotspot();

            $response = json_decode($response,true);

            $hotspots = $response['data']['hotspot'];
            
            return view('admin.mikrotik.hotspot', ['hotspots' => $hotspots]);

        } catch (\Throwable $th) {
            //throw $th;
            dd($th);
        }
    }

}
