<?php

$router->add('', 'Home', 'index');
$router->add('layout', 'Home', 'layout');
$router->add('dashboard', 'Home', 'dashboard');
$router->add('test', 'Test', 'index');

$router->add('trips', 'Trip', 'index');
$router->add('trips/create', 'Trip', 'create');
// $router->add('trips', 'Trip', 'store'); POST
$router->add('trip/{id:\d+}', 'Trip', 'show');
$router->add('trip/{id:\d+}/edit', 'Trip', 'edit');
// $router->add('trip/{id:\d+}', 'Trip', 'update'); PUT/PATCH
// $router->add('trip/{id:\d+}', 'Trip', 'destroy'); DELETE
$router->add('trip/day/{id:\d+}/actions', 'Trip', 'actions'); // AJAX ONLY

$router->add('users', 'User', 'index');