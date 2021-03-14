<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as LaravelTestCase;
use Speelpenning\PostcodeNl\PostcodeNlServiceProvider;

abstract class TestCase extends LaravelTestCase
{
    protected function tearDown(): void
    {
        app()->flush();

        parent::tearDown();
    }

    /**
     * Creates the application.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        $this->configure();
        $app->register(PostcodeNlServiceProvider::class);

        return $app;
    }

    private function configure(): void
    {
        config([
            'postcode-nl' => [
                'requestOptions' => [
                    'auth' => ['key', 'secret'],
                ],
                'enableRoutes' => true,
            ],
        ]);
    }
}
