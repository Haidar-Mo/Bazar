   <?php

   namespace App\Console;

   use Illuminate\Console\Scheduling\Schedule;
   use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

   class Kernel extends ConsoleKernel
   {
       protected function schedule(Schedule $schedule)
       {

        $schedule->command('app:expires-ads')->everyMinute();
        $schedule->command('app:expires-subscription')->everyMinute();
        $schedule->command('app:delete-old-notifications')->dailyAt('00:00');
       }

       protected function commands()
       {
           $this->load(__DIR__.'/Commands');
           require base_path('routes/console.php');
       }
   }

