<?php

namespace Pkboom\TestWatcher\Screens;

use Illuminate\Support\Facades\Config;
use Symfony\Component\Process\Process;

class Phpunit extends Screen
{
    public $options;

    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter ?? '/.*/';
    }

    public function draw()
    {
        $this->runTests()
            ->displayManual();
    }

    public function registerListeners()
    {
        $this->terminal->on('f', function () {
            $this->terminal->displayScreen(new FilterName());
        });

        $this->terminal->on('q', function () {
            exit(0);
        });

        return $this;
    }

    protected function runTests()
    {
        $command = [
            'vendor/bin/phpunit',
            "--filter={$this->filter}",
        ];

        $process = new Process(array_merge($command, Config::get('test-watcher.arguments')));

        $process->setTty(Process::isTtySupported())
            ->setTimeout(Config::get('test-watcher.timeout'))
            ->run(function ($type, $line) {
                echo $line;
            });

        return $this;
    }

    protected function displayManual()
    {
        $this->terminal
            ->emptyLine()
            ->write('(f)ilter, (q)uit, (r)un tests');
    }
}
