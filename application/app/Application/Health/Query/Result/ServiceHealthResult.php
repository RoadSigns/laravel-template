<?php

declare(strict_types=1);

namespace App\Application\Health\Query\Result;

use App\Application\Health\Query\Result\Data\ResourceHealthList;
use JetBrains\PhpStorm\Immutable;

#[Immutable]
final class ServiceHealthResult
{
    public function __construct(
        public bool $isHealthy,
        public ResourceHealthList $resources,
    ) {
    }
}
