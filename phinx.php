<?php

use DigraphCMS\Config;
use DigraphCMS\DB\DB;

require_once __DIR__ . '/vendor/autoload.php';

Config::readFile(__DIR__ . '/digraph.json');
Config::readFile(__DIR__ . '/digraph-env.json');
Config::set('paths.base', realpath(__DIR__));

return
    [
        'paths' => [
            'migrations' => DB::migrationPaths(),
            'seeds' => [],
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'current',
            'current' => Config::get('db'),
        ],
        'version_order' => 'creation',
    ];