<?php
$ok = [];
$errors = [];

// run Apache checks
if (strpos($_SERVER['SERVER_SOFTWARE'], "Apache") !== false) {
    Wizard::confirmation("Supported Server: Apache");
    if (function_exists('apache_get_modules')) {
        var_dump(apache_get_modules());
        Wizard::skippableError("TODO: verify apache modules");
    } else {
        Wizard::skippableError("Unable to check Apache modules");
    }
}

// run dev server checks
elseif (strpos($_SERVER['SERVER_SOFTWARE'], "Development Server") !== false) {
    Wizard::confirmation("Supported Server: PHP Development Server");
}

// this is an unknown server
else {
    Wizard::skippableError('Server may not be automatically supported (' . $_SERVER['SERVER_SOFTWARE'] . ')');
}

// run composer check
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    Wizard::confirmation("Composer dependencies installed");
} else {
    Wizard::error(implode(
        "<br>",
        [
            "Composer dependencies not installed.",
            "Navigate to <code>" . realpath(__DIR__ . '/../..') . "</code> and run one of the following:",
            "For test/development environments just <code>composer update</code> or <code>composer install</code> is fine",
            "For deploying on production servers we recommend adding the options <code>--optimize-autoloader --apcu-autoloader</code>",
        ]
    ));
}
