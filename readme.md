# Laravel Test Watcher

Laravel Test Watcher will rerun tests whenever you save changes.

# Installation

```bash
composer require pkboom/laravel-test-watcher
```

You can publish the config:

```bash
php artisan vendor:publish --provider="Pkboom\TestWatcher\TestWatcherServiceProvider" --tag="config"
```

## Usage

```bash
php artisan test:watch

// with name
php artisan test:watch ExampleTest
```

Then tests will be run.

<img src="/images/demo1.png" width="500"  title="demo">

At the bottom you have three options:

- f: filter
- q: quit

Press f to filter tests.

Type a name you want to filter by. Press `tab` to get help from autocomplete.

<img src="/images/demo2.png" width="500"  title="demo">

To publish the config file to config/test-watcher.php run:

```php
php artisan vendor:publish --provider="Pkboom\TestWatcher\TestWatcherServiceProvider"
```

## License

The MIT License (MIT). Please see [MIT license](http://opensource.org/licenses/MIT) for more information.
