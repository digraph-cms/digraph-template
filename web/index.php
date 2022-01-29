<?php

use DigraphCMS\Config;
use DigraphCMS\Digraph;

// special case for running in PHP's built-in server
// if you won't be using this, you can safely comment it out
// this will save a negligible, but non-zero amount of time
if (php_sapi_name() === 'cli-server') {
    $r = @reset(explode('?', $_SERVER['REQUEST_URI'], 2));
    if ($r == '/favicon.ico' || substr($r, 0, 7) == '/files/' || $r == '/install.php') {
        return false;
    }
}

require_once __DIR__ . "/../vendor/autoload.php";

Digraph::initialize(
    function () {
        // set up config
        // by default config is loaded from digraph.json and digraph-env.json
        Config::readFile(__DIR__ . '/../digraph.json');
        Config::readFile(__DIR__ . '/../digraph-env.json');
        Config::set('paths.base', realpath(__DIR__ . '/..'));
    },
    __DIR__ . '/../cache',
    60
);

// build and render response
Digraph::renderActualRequest();
