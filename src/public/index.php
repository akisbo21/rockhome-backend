<?php

//phpinfo();die();


ini_set('display_errors', 1);

require '../vendor/autoload.php';
require '../app/AppBase.php';
require '../app/App.php';


$app = new App();
$app->handle();



