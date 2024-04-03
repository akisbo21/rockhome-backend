<?php

set_time_limit(10000000);
ini_set('memory_limit', -1);

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/App.php';

$_SERVER['REQUEST_URI'] = $argv[1];

$app = new App();
$app->handle();