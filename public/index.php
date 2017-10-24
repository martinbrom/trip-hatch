<?php

// TODO: displaying errors is turned on/off here
ini_set('display_errors', 1);

require dirname(__DIR__) . '/vendor/autoload.php';

$di = new Core\DependencyInjector();
$di->readFile("services.php");

// TODO: Dashboard should be in trip not home
// TODO: Add route file
// TODO: GET/POST only routes
$router = $di->getService(Core\Router::class);
require_once '../app/routes.php';

// TODO: Add a helper asset() function
$router->dispatch($_SERVER['QUERY_STRING']);