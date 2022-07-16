<?php

declare(strict_types=1);

namespace App\Application\Health\Query\Result\Data;

use ArrayIterator;
use IteratorAggregate;

/**
 * @implements IteratorAggregate<int, ResourceHealth>
 */
final class ResourceHealthList implements IteratorAggregate
{
    /** @var ResourceHealth[] */
    private array $resources;

    public function __construct(ResourceHealth ...$resources)
    {
        $this->resources = $resources;
    }

    /**
     * @return ArrayIterator<int, ResourceHealth>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->resources);
    }
}
