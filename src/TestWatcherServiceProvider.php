<?php

namespace Pkboom\TestWatcher;

use Illuminate\Support\ServiceProvider;

class TestWatcherServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/test-watcher.php' => config_path('test-watcher.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                TestWatchCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/test-watcher.php', 'test-watcher');
    }
}
