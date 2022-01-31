<?php

use DigraphCMS\HTML\Forms\FormWrapper;
use Phinx\Config\Config;
use Phinx\Migration\Manager;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Output\NullOutput;

$form = new FormWrapper();
$form->token()->setCSRF(false);
$form->button()->setText('Begin database setup');
if (!$form->ready()) {
    echo $form;
    Wizard::skippableError('You can also run this step from the command line by running <code>composer run migrate</code> from the project directory');
} else {
    $phinx = new Manager(
        new Config(include(__DIR__ . '/../../phinx.php')),
        new ArrayInput([]),
        new NullOutput()
    );
    $phinx->migrate('current');
    Wizard::confirmation('Database migrations run');
}
