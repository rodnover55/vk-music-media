<?php

namespace VkMusic\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as ParentTestCase;
use PHPUnit_Framework_Assert as PHPUnitAssert;
use Rnr\Alice\FixturesLoader;
use VkMusic\Tests\Support\DatabaseTruncate;

abstract class TestCase extends ParentTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /** @var  FixturesLoader */
    protected $fixtureLoader;

    private $token;

    const ROOT_TEST = __DIR__;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    public function assertResponseStatus($code, $message = null)
    {
        $actual = $this->response->getStatusCode();

        PHPUnitAssert::assertEquals($code, $actual,
            "Expected status code {$code}, got {$actual}.\n" .
            ($message ?? $this->getMessage($actual))
        );

        return $this;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function assertResponseOk($message = null)
    {
        $actual = $this->response->getStatusCode();

        PHPUnitAssert::assertTrue($this->response->isOk(),
            "Expected status code 200, got {$actual}\n" .
            ($message ?? $this->getMessage($actual))
        );

        return $this;
    }

    /**
     * @param int $statusCode
     *
     * @return string
     */
    protected function getMessage(int $statusCode): string
    {
        if ($statusCode >= 500) {
            $message = $this->response->exception;
        } elseif ($statusCode == 422) {
            $message = print_r($this->decodeResponseJson(), true);
        } else {
            $message = '';
        }

        return $message;
    }

    protected function setUpTraits()
    {
        parent::setUpTraits();

        $uses = array_flip(class_uses_recursive(static::class));

        if (isset($uses[DatabaseTruncate::class])) {
            $this->runDatabaseTruncate();
        }
    }

    protected function setUp()
    {
        parent::setUp();

        $this->fixtureLoader = $this->app->make(FixturesLoader::class);
    }

    public function loadFixture($name) {
        $files = is_array($name) ? $name : [$name];

        return $this->fixtureLoader->load(array_map(function ($name) {
            return __DIR__ . "/fixtures/{$name}";
        }, $files));
    }

    protected function auth($token) {
        $this->token = $token;

        return $this;
    }

    public function call(
        $method, $uri, $parameters = [],
        $cookies = [], $files = [],
        $server = [], $content = null
    ) {
        $server = [];

        if (isset($this->token)) {
            $server['HTTP_X_TOKEN'] = $this->token;
        }

        $response = parent::call(
            $method, $uri, $parameters,
            $cookies, $files, $server, $content);

        $this->token = null;

        return $response;
    }

}
