<?php namespace Tests;

use Illuminate\Contracts\Console\Kernel;

/**
 * Trait CreatesApplication
 *
 * This file is included in a fresh Laravel project but not in an OctoberCMS project.
 * This trait is copied from https://github.com/laravel/laravel/blob/develop/tests/CreatesApplication.php
 *
 * @package Tests
 */
trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        return $app;
    }
}
