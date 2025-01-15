<?php

use Kernel\Application\Application;

define('APP_ROOT', dirname(__DIR__));

require_once APP_ROOT.'/vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

$app = new Application;

$app->run();