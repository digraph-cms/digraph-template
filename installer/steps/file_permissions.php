<?php

use DigraphCMS\Config;

$paths = ['cache/', 'storage/', 'web/files/', 'web/install.php', 'digraph.json', 'digraph-env.json'];
$root = Config::get('paths.root');

// check that digraph-env.json exists
if (!is_file("$root/digraph-env.json")) {
    if (file_put_contents("$root/digraph-env.json", "{}")) {
        Wizard::confirmation("Created <code>digraph-env.json</code>");
    } else {
        Wizard::error("You must create a file writeable by PHP at <code>$root/digraph-env.json</code>");
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
