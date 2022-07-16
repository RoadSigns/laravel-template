<?php

declare(strict_types=1);

namespace App\Infrastructure\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use JetBrains\PhpStorm\ArrayShape;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();

        Log::withContext($this->defaultLogContext());

        $env = env('APP_ENV', 'production');
        Model::preventLazyLoading(
            str_contains(strtolower($env), 'local') || str_contains(strtolower($env), 'testing')
        );
    }

    /**
     * @return array<string, string>
     */
    #[ArrayShape(['service' => "string"])]
    private function defaultLogContext(): array
    {
        return [
            'service' => 'laravel',
        ];
    }
}
