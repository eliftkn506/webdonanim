<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Uygulamanın custom artisan komutları
     */
    protected $commands = [
        \App\Console\Commands\OtomatikKuponAta::class,
    ];

    /**
     * Zamanlanmış görevler
     */
    protected function schedule(Schedule $schedule)
    {
        // Örn: kupon atamayı her gece 1'de çalıştır
        // $schedule->command('kupon:otomatik-ata')->dailyAt('01:00');
    }

    /**
     * Artisan komutlarının yüklenmesi
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
 