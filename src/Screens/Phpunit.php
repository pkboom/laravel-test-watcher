<?php

namespace Pkboom\TestWatcher\Screens;

use Illuminate\Support\Facades\Config;
use Symfony\Component\Process\Process;

class Phpunit extends Screen
{
    public const BINARY_PATH = 'vendor/bin/phpunit';

    public $options;

    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter ?? '/.*/';
    }

    public function draw()
    {
        $this->writeHeader()
            ->runTests()
            ->displayManual();
    }

    public function registerListeners()
    {
        $this->terminal->onKeyPress(function ($line) {
            $line = strtolower($line);

            // 'f' pressed, new filter
            switch ($line) {
                case 'f':
                    $this->terminal->displayScreen(new FilterClassName());

                    break;
                case '':
                    $this->terminal->refreshScreen();

                    break;
                case 'q':
                    exit();

                    break;
                default:
                    $this->registerListeners();

                    break;
            }
        });

        return $this;
    }

    protected function writeHeader()
    {
        $this->terminal
            ->emptyLine();

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
