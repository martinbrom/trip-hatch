<?php

$router = di(\Core\Routing\Router::class);

$router->add('GET', '', 'Home', 'index');
$router->add('GET', 'layout', 'Home', 'layout');
// TODO: FAQ, terms etc.

$router->add('GET', 'trips', 'Trip', 'index')->middleware(['auth']);
$router->add('GET', 'trips/create', 'Trip', 'create')->middleware(['auth']);
$router->add('POST', 'trips', 'Trip', 'store')->middleware(['auth']);
$router->add('GET', 'trip/{id:\d+}', 'Trip', 'show')->middleware(['auth']);
$router->add('GET', 'trip/{id:\d+}/edit', 'Trip', 'edit')->middleware(['auth']);
$router->add('PUT', 'trip/{id:\d+}', 'Trip', 'update')->middleware(['auth']);
$router->add('DELETE', 'trip/{id:\d+}', 'Trip', 'destroy')->middleware(['auth']);
$router->add('GET', 'trip/day/{id:\d+}/actions', 'Trip', 'actions')->ajax()->middleware(['auth']);

$router->add('GET', 'users', 'User', 'index');
$router->add('GET', 'login', 'User', 'loginPage');
$router->add('GET', 'forgotten-password', 'User', 'forgottenPasswordPage');
$router->add('POST', 'login', 'User', 'login');
$router->add('POST', 'register', 'User', 'register');
$router->add('GET', 'logout', 'User', 'logout');
$router->add('POST', 'forgotten-password', 'User', 'forgottenPassword');
$router->add('GET', 'profile', 'User', 'profile')->middleware(['auth']);

$router->add('GET', 'testmw', 'Home', 'testMiddleware')->middleware(['TestBeforeMiddleware', 'TestAfterMiddleware']);
