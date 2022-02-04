<?php

use DigraphCMS\Config;
use DigraphCMS\DB\DB;
use DigraphCMS\Initialization\InitializationState;
use DigraphCMS\Initialization\Initializer;
use DigraphCMS\Plugins\Plugins;

require_once __DIR__ . '/vendor/autoload.php';

// load plugins from composer
Plugins::loadFromComposer(__DIR__ . '/composer.lock');

// initialize config
Initializer::run(
    'initialization',
    function (InitializationState $state) {
        $state->mergeConfig(Config::parseYamlFile(__DIR__ . '/digraph.yaml'), true);
        $state->mergeConfig(Config::parseYamlFile(__DIR__ . '/digraph-env.yaml'), true);
        $state->config('paths.base', __DIR__);
    }
);

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
