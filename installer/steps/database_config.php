<?php

use DigraphCMS\Config;
use DigraphCMS\DB\DB;
use DigraphCMS\HTML\Forms\Field;
use DigraphCMS\HTML\Forms\FormWrapper;

$_SESSION['dbmode'] = @$_GET['dbmode'] ?? @$_SESSION['dbmode'];
$displayBack = true;

switch ($_SESSION['dbmode']) {
    default:
        $displayBack = false;
        Wizard::stepError(Wizard::ERROR_INCOMPLETE);
        echo "<h2>Choose database location</h2>";
        printf(
            "<p><a href='?dbmode=sqlite' class='button'>Use a SQLite database file</a><br><small>Database will be stored in a SQLite file in <code>%s</code></small></p>",
            Config::get('paths.storage')
        );
        printf(
            "<p><a href='?dbmode=connect' class='button'>Connect to a database server</a><br><small>Define the connection parameters to use a separate database server</small></p>"
        );
        break;
    case 'sqlite':
        Wizard::unsetConfig('db');
        Wizard::confirmation('Database setting written to <code>digraph-env.json</code>');
        echo '<p>No configuration is necessary to use a SQLite database.</p>';
        break;
    case 'connect':
        $host = (new Field('Host'))
            ->setRequired(true)
            ->setDefault(Wizard::getConfig('db.host'));
        $port = (new Field('Port (if different from default)'))
            ->setDefault(Wizard::getConfig('db.port'));
        $name = (new Field('Database name'))
            ->setRequired(true)
            ->setDefault(Wizard::getConfig('db.name'));
        $user = (new Field('Username'))
            ->setDefault(Wizard::getConfig('db.user'));
        $pass = (new Field('Password'))
            ->setDefault(Wizard::getConfig('db.pass'));
        $form = (new FormWrapper())
            ->addChild($host)
            ->addChild($port)
            ->addChild($name)
            ->addChild($user)
            ->addChild($pass);
        $form->token()->setCSRF(false);
        $form->button()->setText('Check database configuration');
        if ($form->ready()) {
            $dbConfig = [
                'adapter' => 'mysql',
                'host' => $host->value(),
                'port' => $port->value() ? $port->value() : null,
                'name' => $name->value() ? $name->value() : null,
                'user' => $user->value() ? $user->value() : null,
                'pass' => $pass->value() ? $pass->value() : null
            ];
            try {
                Config::set('db', 'false');
                Config::set('db', $dbConfig);
                DB::pdo();
                Wizard::confirmation('Database connection successful');
                Wizard::setConfig('db', $dbConfig);
                Wizard::confirmation('Database setting written to <code>digraph-env.json</code>');
            } catch (\Throwable $th) {
                Wizard::error('Error: ' . $th->getMessage());
            }
        } else {
            Wizard::stepError(Wizard::ERROR_INCOMPLETE);
        }
        echo $form;
        break;
}

if ($displayBack) {
    echo "<hr>";
    echo "<a href='?dbmode=' class='button'>Back to database location selection</a>";
}
