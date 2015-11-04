<?php

use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Speelpenning\PostcodeNl\PostcodeNlServiceProvider;

abstract class TestCase extends LaravelTestCase
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
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $app->register(PostcodeNlServiceProvider::class);

        return $app;
    }
}
