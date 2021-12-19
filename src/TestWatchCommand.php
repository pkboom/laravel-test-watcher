<?php

namespace Pkboom\TestWatcher;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use React\EventLoop\Loop;
use ReflectionClass;
use ReflectionMethod;
use Symfony\Component\Finder\Finder;

class TestWatchCommand extends Command
{
    protected $signature = 'test:watch {class}';

    public function handle()
    {
        if (!($class = $this->argument('class'))) {
            $this->error('Need a test class name');

            return 1;
        }

        // accept class name
        // if multiple, let a use select one.
        // if not multiple, go on

        // We need a filename to notice a change in a file

        $file = collect((new Finder())->in(base_path('tests'))->files()->name('*.php'))
            ->first(fn ($file) => str_contains(file_get_contents($file->getRealPath()), $class));

        $this->createTestWatcher($file->getRealPath(), $test);
    }

    public function createTestWatcher($file, $test)
    {
        $lastModifiedTimestamp = 0;

        $timer = Loop::addPeriodicTimer(1, function () use (&$lastModifiedTimestamp, $file, &$test) {
            clearstatcache();

            if ($lastModifiedTimestamp !== filemtime($file)) {
                $lastModifiedTimestamp = filemtime($file);

                $command = "php artisan test --filter '^.*::{$test}( .*)?$' --stop-on-failure --order-by=defects";

                echo $command;

                system($command, $result);

                if ($result === 0) {
                    $namespace = 'Tests\\';

                    $class =  $namespace.str_replace(
                        ['/', '.php'],
                        ['\\', ''],
                        Str::after($file, realpath(base_path('tests')).DIRECTORY_SEPARATOR)
                    );

                    $reflection = new ReflectionClass($class);

                    $methods = $reflection->getMethods(ReflectionMethod::IS_PUBLIC);

                    foreach ($methods as $value) {
                        if ($value->getFileName() === $file) {
                            $results[] = $value->getName();
                        }
                    }

                    $test = $this->choice(
                        'What do you want to test?',
                        $results
                    );
                }

                echo $test;
            }
        });
    }
}
