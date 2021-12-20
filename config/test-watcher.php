<?php

return [
    // Name accepts globs, strings, regexes or an array of globs, strings or regexes
    // '*.php', '/\.php$/', ['*.php', '*.twig']
    'name' => '*.php',

    // A directory path or an array of directories
    'exclude' => [],

    // A directory path or an array of directories
    'in' => ['app', 'tests'],

    // phpunit arguments
    'arguments' => ['--stop-on-failure', '--order-by=defects'],

    // timeout, default 10min
    'timeout' => 600,
];
