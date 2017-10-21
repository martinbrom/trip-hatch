<?php

// TODO: displaying errors is turned on/off here
ini_set('display_errors', 1);

require dirname(__DIR__) . '/vendor/autoload.php';

$router = new App\Core\Router();

// TODO: Add route file
// TODO: GET/POST only routes
$router->add('', 'Home', 'index');
$router->add('trip/{id:\d+}', 'Trip', 'tripShow');
$router->add('trip/{id:\d+}/comment/{cid:\d+}', 'Trip', 'tripCommentShow');

$router->dispatch($_SERVER['QUERY_STRING']);