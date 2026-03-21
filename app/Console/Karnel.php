<?php
// app/Console/Kernel.php

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
        \App\Console\Commands\ManagePermissionGroups::class,
        \App\Console\Commands\CreatePermissionSeeder::class,
        \App\Console\Commands\CheckPermissions::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
         // Nettoyer les logs de plus de 30 jours
    $schedule->command('log:clean')->daily();
    
    // Vérifier les campagnes en échec
    $schedule->command('mail:check-logs --errors-only')->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}