<?php

declare(strict_types=1);

namespace App\Transport\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

final class Kernel extends ConsoleKernel
{
    /**
     * Output should be sent to /proc/1/fd/1 to be picked up by Cloudwatch.
     */
    private const SCHEDULE_OUTPUT_PATH = '/proc/1/fd/1';

    /**
     * The Artisan commands provided by your application.
     *
     * @var class-string[]
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Command');

        require __DIR__ . '/../../../routes/console.php';

    }
}
