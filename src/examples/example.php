<?php

require_once __DIR__.'/../../vendor/autoload.php';

$ipReverse = new Arall\IpReverse(isset($argv[1]) ? $argv[1] : '69.50.225.155');

$hosts = $ipReverse->hosts;
if ($hosts && !empty($hosts)) {
    echo 'Hosts: ' . implode('\n', $hosts). PHP_EOL;
} else {
    echo 'No hosts found'. PHP_EOL;
}
