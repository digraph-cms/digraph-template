<?php

use DigraphCMS\Config;

$paths = ['cache/', 'storage/', 'web/files/', 'web/install.php', 'digraph.yaml', 'digraph-env.yaml'];
$root = Config::get('paths.base');

// check that digraph-env.yaml exists
if (!is_file("$root/digraph-env.yaml")) {
    if (file_put_contents("$root/digraph-env.yaml", "{}")) {
        Wizard::confirmation("Created <code>digraph-env.yaml</code>");
    } else {
        Wizard::error("You must create a file writeable by PHP at <code>$root/digraph-env.yaml</code>");
    }
}

// check writeability of all paths
foreach ($paths as $path) {
    $path = "$root/$path";
    if (file_exists($path) || is_dir($path)) {
        if (is_writeable($path)) {
            Wizard::confirmation("<code>$path</code> is writeable");
        }else {
            Wizard::error("<code>$path</code> must be writeable by PHP (i.e. chmod 0770 on many servers)");
        }
    }else {
        Wizard::error("<code>$path</code> must exist");
    }
}
