#! /usr/bin/env php

<?php

use Symfony\Component\Console\Application;

// Composer
if (!file_exists('vendor/autoload.php')) {
    die('Composer dependency manager is needed: https://getcomposer.org/');
}
require 'vendor/autoload.php';

$app = new Application('IP Reverse', '1.0');

$app->add(new Arall\IPReverse\Commands\IPReverse());

try {
    $app->run();
} catch (Exception $e) {
    die($e->getMessage());
}
