<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Log;
use DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function() {

            try {
                
                $result = app('App\Http\Controllers\API\MikrotikController')->getSpeedAllConnectedUser();

                $result = json_decode($result);
    
                $result = $result->data->queues;
    
                $arr_username = [];
    
                DB::connection('mysql')->transaction(function() use(&$arr_username, &$result){
    
                    //ambil username
                    $users = DB::connection('mysql')->table('tb_average_speed')
                                ->select('username')->groupBy('username')->get();
    
                    foreach($users as $user){
                        array_push($arr_username, $user->username);
                    }
    
                    for($i = 0; $i < count($result); $i++){
    
                        if(!in_array($result[$i]->username, $arr_username)){
    
                            DB::connection('mysql')->table('tb_average_speed')->insert([
                                'username' => $result[$i]->username,
                                'upload_speed' => $result[$i]->upload_speed,
                                'download_speed' => $result[$i]->download_speed,
                                'count' => 0,
                                'created_at' => date('Y-m-d'),
                            ]);
    
                        } else {
                            
                            $user = DB::connection('mysql')->table('tb_average_speed')
                                        ->where('username', $result[$i]->username)
                                        ->latest('created_at')
                                        ->first();
                            
                            if($user->created_at == date('Y-m-d')){
    
                                DB::connection('mysql')->table('tb_average_speed')
                                    ->where('username', $result[$i]->username)
                                    ->whereDate('created_at', date('Y-m-d'))
                                    ->update([
                                        'upload_speed' => $user->upload_speed + $result[$i]->upload_speed,
                                        'download_speed' => $user->download_speed + $result[$i]->download_speed,
                                        'count' => $user->count + 1,
                                    ]);
    
                            } else {
    
                                DB::connection('mysql')->table('tb_average_speed')->insert([
                                    'username' => $result[$i]->username,
                                    'upload_speed' => $result[$i]->upload_speed,
                                    'download_speed' => $result[$i]->download_speed,
                                    'count' => 0,
                                    'created_at' => date('Y-m-d'),
                                ]);
    
                            }
    
                        }
    
                    }
                });

                Log::info("success running");

            } catch (\Exception $e) {
                Log::warning($e->getMessage());
            }

            

        })->everyMinute()->appendOutputTo(public_path('log/cron_log.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
