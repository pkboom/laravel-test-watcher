<?php

namespace Pkboom\TestWatcher\Screens;

use Illuminate\Support\Str;

class FilterClassName extends Screen
{
    public function draw()
    {
        $this->terminal
            ->comment('Type class name.')
            ->prompt('> ');

        return $this;
    }

    public function registerListeners()
    {
        $this->terminal->on('data', function ($line) {
            if ($line === '') {
                $this->terminal->goBack();

                return;
            }

            $this->terminal->displayScreen(new Phpunit($line));
        });

        $this->registerAutocompleter();

        return $this;
    }

    protected function registerAutocompleter()
    {
        $this->terminal->setAutocomplete(function ($word) {
            return $this->terminal->files->filter(function ($file) {
                return str_contains($file, 'Test.php');
            })->map(function ($file) {
                return (string) Str::of($file)->afterLast('/')
                    ->before('.php');
            })->filter(function ($class) use ($word) {
                return str_contains($class, $word);
            })->values()->toArray();
        });
    }
}
