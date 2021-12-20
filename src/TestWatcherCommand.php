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
    protected $signature = 'test:watch {--filter=}';

    protected $terminal;

    public function handle()
    {
        $this->terminal = new Terminal(new Stdio());

        $finder = (new Finder())
            ->name(Config::get('test-watcher.name'))
            ->files()
            ->exclude(Config::get('test-watcher.exclude'))
            ->in(Config::get('test-watcher.in'));

        $this->terminal->finder($finder)
            ->displayScreen(new Phpunit($this->option('filter')), false);

        $watcher = FileWatcher::create($finder);

        Loop::addPeriodicTimer(1, function () use ($watcher) {
            $watcher->find()->whenChanged(function () {
                $this->terminal->refreshScreen();
            });
        });
    }
}
