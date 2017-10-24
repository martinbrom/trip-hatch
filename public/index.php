<?php

// TODO: displaying errors is turned on/off here
ini_set('display_errors', 1);

require dirname(__DIR__) . '/vendor/autoload.php';

$di = new Core\DependencyInjector();
$di->readFile("services.php");

// TODO: Add route file
// TODO: GET/POST only routes
$router = $di->getService(Core\Router::class);
$router->add('', 'Home', 'index');
$router->add('layout', 'Home', 'layout');
$router->add('dashboard', 'Home', 'dashboard');

$router->add('test', 'Test', 'index');

// TODO: Add a helper asset() function
$router->dispatch($_SERVER['QUERY_STRING']);