<?php

declare(strict_types=1);

namespace LaravelTest;

use Illuminate\Testing\TestResponse;

abstract class BehatTestCase extends TestCase
{
    /**
     * @var array<string, string>
     */
    protected array $authHeaders = [];

    /**
     * @BeforeScenario
     */
    public function setupLaravel(): void
    {
        // Ensure we reset the application environment to testing before application spawns in case
        // it was set int he ApplicationEnvironmentAwareContext trait.
        $_SERVER['APP_ENV'] = 'testing';
        $_SERVER['BCRYPT_ROUNDS'] = '4';
        $_SERVER['CACHE_DRIVER'] = 'array';
        $_SERVER['DB_CONNECTION'] = 'test';
        $_SERVER['MAIL_MAILER'] = 'array';
        $_SERVER['QUEUE_CONNECTION'] = 'sync';
        $_SERVER['SESSION_DRIVER'] = 'array';
        $_SERVER['TELESCOPE_ENABLED'] = 'false';
        $_SERVER['LOG_CHANNEL'] = 'test';

        parent::setUp();
    }

    /**
     * @AfterScenario
     */
    public function tearDownLaravel(): void
    {
        parent::tearDown();
    }

    /**
     * @BeforeScenario
     */
    public function clearAuthHeaders(): void
    {
        $this->authHeaders = [];
    }

    /**
     * @param array<string, string> $headers
     */
    public function getJson($uri, array $headers = []): TestResponse
    {
        return parent::getJson($uri, array_merge($this->authHeaders, $headers));
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function postJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::postJson($uri, $data, array_merge($this->authHeaders, $headers));
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function putJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::putJson($uri, $data, array_merge($this->authHeaders, $headers));
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function patchJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::patchJson($uri, $data, array_merge($this->authHeaders, $headers));
    }

    /**
     * @param array<string, mixed> $data
     * @param array<string, string> $headers
     */
    public function deleteJson($uri, array $data = [], array $headers = []): TestResponse
    {
        return parent::deleteJson($uri, $data, array_merge($this->authHeaders, $headers));
    }
}
