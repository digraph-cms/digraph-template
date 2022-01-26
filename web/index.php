<?php

use DigraphCMS\Config;
use DigraphCMS\Digraph;
use DigraphCMS\URL\URLs;

require_once __DIR__ . "/../vendor/autoload.php";

// set up config
// by default config is loaded from digraph.json and digraph-env.json
Config::readFile(__DIR__ . '/../digraph.json');
Config::readFile(__DIR__ . '/../digraph-env.json');
Config::set('paths.base', realpath(__DIR__ . '/..'));

// special case for running in PHP's built-in server
// if you won't be using this, you can safely comment it out
// this will save a negligible, but non-zero amount of time
if (php_sapi_name() === 'cli-server') {
    URLs::$sitePath = '';
    $url = Digraph::actualUrl();
    if ($url->path() == '/favicon.ico' || substr($url->path(), 0, 7) == '/files/' || $url->path() == '/install.php') {
        return false;
    }
}

// build and render response
Digraph::renderActualRequest();
