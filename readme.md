# Laravel Dump Tinker

Inspired by [beyondcode/laravel-dump-server](https://github.com/beyondcode/laravel-dump-server) and [spatie/laravel-web-tinker](https://github.com/spatie/laravel-web-tinker).

[![Latest Stable Version](https://poser.pugx.org/pkboom/laravel-test-watcher/v/stable)](https://packagist.org/packages/pkboom/laravel-test-watcher)
[![Total Downloads](https://poser.pugx.org/pkboom/laravel-test-watcher/downloads)](https://packagist.org/packages/pkboom/laravel-test-watcher)

You can output result to `output.json` after writing code in `input.php`.

<img src="/images/demo2.png" width="800">

With query:

<img src="/images/demo1.png" width="800">

You can also output data to the console with `dd()` or `dump()`.

<img src="/images/demo3.png" width="800">

## Installation

You can install the package via composer:

```bash
composer require pkboom/laravel-test-watcher --dev
```

you can optionally publish the config file.

```bash
php artisan vendor:publish --provider=Pkboom\\TestWatcher\\TestWatcherServiceProvider
```

## Usage

```bash
php artisan tinker:dump
```

You can show queries with an option:

```bash
php artisan tinker:dump --query
```

## License

The MIT License (MIT). Please see [MIT license](http://opensource.org/licenses/MIT) for more information.
