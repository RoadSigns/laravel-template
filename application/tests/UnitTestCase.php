<?php

declare(strict_types=1);

namespace LaravelTest;

use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

abstract class UnitTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        // Reset Carbon before each test.
        Carbon::setTestNow();
    }
}
