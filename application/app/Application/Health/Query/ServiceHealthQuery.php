<?php

declare(strict_types=1);

namespace App\Application\Health\Query;

use App\Application\Health\Query\Result\Data\ResourceHealth;
use App\Application\Health\Query\Result\Data\ResourceHealthList;
use App\Application\Health\Query\Result\ServiceHealthResult;
use Closure;
use Exception;
use Illuminate\Support\Facades\DB;
use Throwable;

final class ServiceHealthQuery
{
    public function result(): ServiceHealthResult
    {
        $resources = new ResourceHealthList(
            $this->databaseConnectionHealth()
        );

        $isHealthy = collect($resources)->reduce(
            fn (bool $healthy, ResourceHealth $resourceHealth): bool => !$resourceHealth->isHealthy ? false : $healthy,
            true,
        );

        return new ServiceHealthResult(
            isHealthy: $isHealthy,
            resources: $resources,
        );
    }

    private function databaseConnectionHealth(): ResourceHealth
    {
        return $this->tryService(
            'database',
            function (): bool {
                try {
                    DB::connection()->getPDO();
                    DB::connection()->getDatabaseName();
                    return true;
                } catch (Exception $exception) {
                    return false;
                }
            }
        );
    }

    private function tryService(string $service, Closure $check): ResourceHealth
    {
        try {
            $check();
        } catch (Throwable $t) {
            return new ResourceHealth(
                name: $service,
                isHealthy: false,
                error: $t->getMessage(),
            );
        }

        return new ResourceHealth(
            name: $service,
            isHealthy: true,
            error: '',
        );
    }
}
