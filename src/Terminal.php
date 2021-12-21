<?php

namespace Pkboom\TestWatcher;

use Clue\React\Stdio\Stdio;
use Pkboom\TestWatcher\Screens\Screen;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Finder\Finder;

class Terminal
{
    protected $io;

    protected $currentScreen = null;

    public $finder;

    public function __construct(Stdio $io)
    {
        $this->io = $io;
    }

    public function finder(Finder $finder)
    {
        $this->finder = $finder;

        return $this;
    }

    public function on(string $eventName, callable $callable)
    {
        $this->io->on($eventName, function ($line) use ($callable) {
            $callable(trim($line));
        });

        return $this;
    }

    public function emptyLine()
    {
        $this->write('');

        return $this;
    }

    public function comment(string $message)
    {
        $this->write($message, 'comment');

        return $this;
    }

    public function write(string $message = '', $level = null)
    {
        $formattedMessage = (new OutputFormatter(true))->format($message);

        $formattedMessage = str_replace('<dim>', "\e[2m", $formattedMessage);
        $formattedMessage = str_replace('</dim>', "\e[22m", $formattedMessage);

        $this->io->write($formattedMessage.PHP_EOL);

        return $this;
    }

    public function displayScreen(Screen $screen, $clearScreen = true)
    {
        $this->currentScreen = $screen;

        $screen->useTerminal($this)
            ->promptReady()
            ->removeAllListeners()
            ->registerListeners();

        if ($clearScreen) {
            $screen->clear();
        }

        $screen->draw();

        return $this;
    }

    public function refreshScreen()
    {
        $this->displayScreen($this->currentScreen);

        return $this;
    }

    public function removeAllListeners()
    {
        $this->io->removeAllListeners();

        return $this;
    }

    public function prompt(string $prompt)
    {
        $this->io->setPrompt($prompt);

        return $this;
    }

    public function promptReady()
    {
        $this->io->setPrompt('');

        return $this;
    }

    public function setAutocomplete($callback)
    {
        $this->io->setAutocomplete($callback);

        return $this;
    }
}
