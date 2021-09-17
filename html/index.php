<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use Base\Application;
require '../vendor/autoload.php';
require '../base/config.php';
require '../base/eloquent.php';


$app = new Application();
$app->run();