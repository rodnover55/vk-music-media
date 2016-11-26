<?php

namespace VkMusic\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\TestCase as ParentTestCase;
use PHPUnit_Framework_Assert as PHPUnitAssert;
use VkMusic\Tests\Support\DatabaseTruncate;

abstract class TestCase extends ParentTestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

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
        } elseif ($statusCode >= 400) {
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
}
