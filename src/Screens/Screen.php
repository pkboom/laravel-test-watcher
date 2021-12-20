<?php

namespace Pkboom\TestWatcher\Screens;

use Pkboom\TestWatcher\Terminal;

abstract class Screen
{
    protected $terminal;

    public function useTerminal(Terminal $terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }

    public function draw()
    {
        return $this;
    }

    public function registerListeners()
    {
        return $this;
    }

    public function clear()
    {
        echo "\033\143";

        return $this;
    }

    public function removeAllListeners()
    {
        $this->terminal->removeAllListeners();

        return $this;
    }

    public function promptReady()
    {
        $this->terminal->promptReady();

        return $this;
    }
}
