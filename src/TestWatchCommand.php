<?php

namespace Pkboom\TestWatcher;

use Illuminate\Console\Command;
use React\EventLoop\Loop;
use Symfony\Component\Finder\Finder;

class TestWatchCommand extends Command
{
    protected $signature = 'test:watch {test}';

    public function handle()
    {
        if (!($test = $this->argument('test'))) {
            $this->error('Need a test name');

            return 1;
        }

        $file = collect((new Finder())->in(base_path('tests'))->files()->name('*.php'))
            ->first(fn ($file) => str_contains(file_get_contents($file->getRealPath()), $test));

        $this->createTestWatcher($file->getRealPath(), $test);
    }

    public function createTestWatcher($file, $test)
    {
        $lastModifiedTimestamp = 0;

        Loop::addPeriodicTimer(1, function () use (&$lastModifiedTimestamp, $file, $test) {
            clearstatcache();

            if ($lastModifiedTimestamp !== filemtime($file)) {
                $lastModifiedTimestamp = filemtime($file);

                $command = "php artisan test $file --filter '^.*::{$test}( .*)?$' --stop-on-failure --order-by=defects";

                exec($command);
            }
        });
    }
}
