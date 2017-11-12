<?php

$router = di(\Core\Routing\Router::class);

$router->add('GET', '', 'Home', 'index');
$router->add('GET', 'layout', 'Home', 'layout');
// TODO: FAQ, terms etc.

$router->add('GET', 'trips', 'Trip', 'index')->middleware(['auth']);
$router->add('GET', 'trips/create', 'Trip', 'create')->middleware(['auth']);
$router->add('POST', 'trips', 'Trip', 'store')->middleware(['auth'])->validate(['title' => ['max:100', 'notnull']]);
$router->add('GET', 'trip/{id:\d+}', 'Trip', 'show')->middleware(['auth'])->validate(['id' => ['exists:trips']]);
$router->add('GET', 'trip/{id:\d+}/edit', 'Trip', 'edit')->middleware(['auth'])->validate(['id' => ['exists:trips']]);
$router->add('PUT', 'trip/{id:\d+}', 'Trip', 'update')->middleware(['auth'])->validate(['id' => ['exists:trips']]);
$router->add('DELETE', 'trip/{id:\d+}', 'Trip', 'destroy')->middleware(['auth'])->validate(['id' => ['exists:trips']]);

// TODO: Regex for string???
$router->add('GET', 'trip/public/{public_url}', 'Trip', 'showPublic')->validate(['public_url' => ['notnull', 'exists:trips']]);
$router->add('GET', 'trip/{id:\d+}/publish', 'Trip', 'publish')
    ->middleware(['auth', 'trip-owner'])
    ->validate(['id' => ['exists:trips']])
    ->ajax();
$router->add('GET', 'trip/{id:\d+}/classify', 'Trip', 'classify')
    ->middleware(['auth', 'trip-owner'])
    ->validate(['id' => ['exists:trips']])
    ->ajax();

// TODO: Maybe POST?
$router->add('GET', 'trip/day/{id:\d+}/actions', 'Trip', 'actions')->ajax()->middleware(['auth'])->validate(['id' => ['exists:days']]);

// TODO: Maybe POST?
$router->add('GET', 'action-types', 'Trip', 'actionTypes')->ajax();

$router->add('GET', 'users', 'User', 'index');
$router->add('GET', 'login', 'User', 'loginPage');
$router->add('GET', 'forgotten-password', 'User', 'forgottenPasswordPage');
$router->add('POST', 'login', 'User', 'login')->validate(['email' => ['email', 'max:255']]);
$router->add('POST', 'register', 'User', 'register');
$router->add('GET', 'logout', 'User', 'logout');
$router->add('POST', 'forgotten-password', 'User', 'forgottenPassword');
$router->add('GET', 'profile', 'User', 'profile')->middleware(['auth']);

$router->add('GET', 'testmw', 'Home', 'testMiddleware')->middleware(['test1', 'test2']);
