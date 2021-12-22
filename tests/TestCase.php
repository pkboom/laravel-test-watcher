<?php

namespace Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Pkboom\TestWatcher\TestWatcherServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            TestWatcherServiceProvider::class,
        ];
    }
}
