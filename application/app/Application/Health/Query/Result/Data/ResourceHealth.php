<?php

declare(strict_types=1);

namespace App\Application\Health\Query\Result\Data;

use JetBrains\PhpStorm\Immutable;
use Stringable;

#[Immutable]
final class ResourceHealth
{
    public function __construct(
        public string $name,
        public bool $isHealthy,
        public string | Stringable $error,
    ) {
    }
}
