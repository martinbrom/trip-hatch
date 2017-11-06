<?php

$router = di(\Core\Router::class);

$router->add('GET', '', 'Home', 'index');
$router->add('GET', 'layout', 'Home', 'layout');
$router->add('GET', 'dashboard', 'Home', 'dashboard');

$router->add('GET', 'trips', 'Trip', 'index');
$router->add('GET', 'trips/create', 'Trip', 'create');
$router->add('POST', 'trips', 'Trip', 'store');
$router->add('GET', 'trip/{id:\d+}', 'Trip', 'show');
$router->add('GET', 'trip/{id:\d+}/edit', 'Trip', 'edit');
$router->add('PUT', 'trip/{id:\d+}', 'Trip', 'update');
$router->add('DELETE', 'trip/{id:\d+}', 'Trip', 'destroy');
$router->add('GET', 'trip/day/{id:\d+}/actions', 'Trip', 'actions')->ajax();

$router->add('GET', 'users', 'User', 'index');
$router->add('GET', 'login', 'User', 'loginPage');
$router->add('POST', 'login', 'User', 'login');
$router->add('POST', 'register', 'User', 'register');

$router->add('GET', 'testmw', 'Home', 'testMiddleware')->middleware(['TestBeforeMiddleware', 'TestAfterMiddleware']);
