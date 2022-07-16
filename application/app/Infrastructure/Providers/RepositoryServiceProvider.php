<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;

final class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All the container singletons that should be registered.
     *
     * @var array<class-string, class-string>
     */
    public array $singletons = [];

    /**
     * Register any application services.
     */
    public function register(): void
    {
    }
}
