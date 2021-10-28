<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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

            $result = app('App\Http\Controllers\API\MikrotikController')->getSpeedAllConnectedUser();

            $result = json_decode($result);

            $result = $result->data->queues;

            $arr_username = [];

            DB::connecton('mysql')->transaction(function() use($arr_username, $result){

                //ambil username
                $users = DB::connection('mysql')->table('tb_average_speed')->select('username')->get();

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
                                    ->select('upload_speed', 'download_speed', 'count', 'created_at')
                                    ->where('username', $result[$i]->username)
                                    ->whereRaw('MAX(created_at)')
                                    ->first();
                        
                        if($user->created_at == date('Y-m-d')){

                            DB::connection('mysql')->table('tb_average_speed')
                                ->where('username', $result[$i]->username)
                                ->update([
                                    'upload_speed' => $users[$i]->upload_speed + $result[$i]->upload_speed,
                                    'download_speed' => $users[$i]->download_speed + $result[$i]->download_speed,
                                    'count' => $users[$i]->count + 1,
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

        })->everyMinutes()->appendOutputTo(public_path('log/cron_log.log'));
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
