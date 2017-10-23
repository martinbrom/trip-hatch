<?php

// TODO: displaying errors is turned on/off here
ini_set('display_errors', 1);

require dirname(__DIR__) . '/vendor/autoload.php';

$di = new \App\Core\DependencyInjector();
$di->readFile("services.php");

// TODO: Add route file
// TODO: GET/POST only routes
$router = new App\Core\Router();
$router->add('', 'Home', 'index');
$router->add('layout', 'Home', 'layout');

// TODO: Add a helper asset() function
//just for testing now
/*
$router->add('trip/{id:\d+}', 'Trip', 'tripShow');
$router->add('trip/{id:\d+}/comment/{cid:\d+}', 'Trip', 'tripCommentShow');
*/

$router->dispatch($_SERVER['QUERY_STRING']);