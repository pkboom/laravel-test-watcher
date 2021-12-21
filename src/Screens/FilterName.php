<?php

namespace Pkboom\TestWatcher\Screens;

use Illuminate\Support\Str;

class FilterName extends Screen
{
    public function draw()
    {
        $this->terminal
            ->comment('Type name:')
            ->prompt('> ');

        return $this;
    }

    public function registerListeners()
    {
        $this->terminal->on('data', function ($line) {
            if ($line === '') {
                return;
            }

            $this->terminal->displayScreen(new Phpunit($line));
        });

        $this->registerAutocompleter();

        return $this;
    }

    protected function registerAutocompleter()
    {
        return $this->terminal->setAutocomplete(function ($word) {
            return collect($this->terminal->finder)->filter(function ($file, $key) {
                return str_contains($key, 'Test.php');
            })->map(function ($file, $key) {
                return (string) Str::of($key)->afterLast('/')
                    ->before('.php');
            })->filter(function ($class) use ($word) {
                return str_contains($class, $word);
            })->values()->toArray();
        });
    }
}
