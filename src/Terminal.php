<?php

namespace Pkboom\TestWatcher;

use Clue\React\Stdio\Stdio;
use Illuminate\Support\Collection;
use Pkboom\TestWatcher\Screens\Screen;
use Symfony\Component\Console\Formatter\OutputFormatter;

class Terminal
{
    protected $io;

    protected $previousScreen = null;

    protected $currentScreen = null;

    public function __construct(Stdio $io)
    {
        $this->io = $io;
    }

    public function files(Collection $files)
    {
        $this->files = $files;

        return $this;
    }

    public function on(string $eventName, callable $callable)
    {
        $this->io->on($eventName, function ($line) use ($callable) {
            $callable(trim($line));
        });

        return $this;
    }

    public function onKeyPress(callable $callable)
    {
        $this->io->once('data', function ($line) use ($callable) {
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
        $this->previousScreen = $this->currentScreen;

        $this->currentScreen = $screen;

        $screen->useTerminal($this)
            ->clearPrompt()
            ->removeAllListeners()
            ->registerListeners();

        if ($clearScreen) {
            $screen->clear();
        }

        $screen->draw();

        return $this;
    }

    public function goBack()
    {
        if (is_null($this->previousScreen)) {
            return;
        }

        $this->currentScreen = $this->previousScreen;

        $this->displayScreen($this->currentScreen);

        return $this;
    }

    public function getPreviousScreen(): Screen
    {
        return $this->previousScreen;
    }

    public function refreshScreen()
    {
        if (is_null($this->currentScreen)) {
            return;
        }

        $this->displayScreen($this->currentScreen);

        return $this;
    }

    public function isDisplayingScreen(string $screenClassName): bool
    {
        if (is_null($this->currentScreen)) {
            return false;
        }

        return $screenClassName === get_class($this->currentScreen);
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

    public function clearPrompt()
    {
        $this->io->setPrompt('');

        return $this;
    }

    public function setAutocomplete($callback)
    {
        $this->io->setAutocomplete($callback);

        return $this;
    }

    public function getStdio()
    {
        return $this->io;
    }

    public function setInput($data)
    {
        return $this->io->setInput($data);
    }

    public function moveCursorBy($data)
    {
        return $this->io->moveCursorBy($data);
    }
}
