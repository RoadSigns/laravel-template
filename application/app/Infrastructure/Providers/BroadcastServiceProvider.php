<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

final class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Broadcast::routes();
        require __DIR__ . '/../../../routes/channels.php';
    }
}
