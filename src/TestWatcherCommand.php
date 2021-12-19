<?php

namespace Pkboom\TestWatcher;

use Clue\React\Stdio\Stdio;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Pkboom\FileWatcher\FileWatcher;
use Pkboom\TestWatcher\Screens\Phpunit;
use React\EventLoop\Loop;
use Symfony\Component\Finder\Finder;

class TestWatcherCommand extends Command
{
    protected $signature = 'test:watch';
    // protected $signature = 'test:watch {class}';

    protected $terminal;

    public function __construct()
    {
        parent::__construct();

        $this->terminal = new Terminal(new Stdio());
    }

    public function handle()
    {
        // if (!($class = $this->argument('class'))) {
        //     $this->error('Need a test class name');

        //     return 1;
        // }

        // accept class name
        // if multiple, let a use select one.
        // if not multiple, go on

        // We need a filename to notice a change in a file

        $this->startWatching();
    }

    public function startWatching()
    {
        $this->terminal->displayScreen(new Phpunit(), false);

        $finder = (new Finder())
            ->name(Config::get('test-watcher.name'))
            ->files()
            ->exclude(Config::get('test-watcher.exclude'))
            ->in(Config::get('test-watcher.in'));

        $watcher = FileWatcher::create($finder);

        Loop::addPeriodicTimer(1, function () use ($watcher) {
            dump('sdf');
            $watcher->find()->whenChanged(function () {
                // $this->terminal->refreshScreen();

                $command = 'php artisan test '.Config::get('test-watcher.arguments');

                exec($command);
            });
        });
    }
}
