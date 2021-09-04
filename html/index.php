<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

use Base\Application;
include '../base/config.php';
include '../vendor/autoload.php';

$app = new Application();
$app->run();