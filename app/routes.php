<?php

$router = di(\Core\Routing\Router::class);

$router->add('GET', '', 'Home', 'index');
$router->add('GET', 'layout', 'Home', 'layout');
$router->add('GET', 'testvalidate', 'Home', 'testValidation')->validate(['a' => ['between:10,100', 'email'], 'b' => ['max:30']]);
// TODO: FAQ, terms etc.

$router->add('GET', 'trips', 'Trip', 'index')->middleware(['auth']);
$router->add('GET', 'trip/create', 'Trip', 'create')->middleware(['auth']);
$router->add('POST', 'trips', 'Trip', 'store')->middleware(['auth'])->validate(['title' => ['required', 'max:100']]);
$router->add('GET', 'trip/{id:\d+}', 'Trip', 'show')->middleware(['auth']);
$router->add('GET', 'trip/{id:\d+}/edit', 'Trip', 'edit')->middleware(['auth']);
$router->add('PUT', 'trip/{id:\d+}', 'Trip', 'update')->middleware(['auth']);
$router->add('DELETE', 'trip/{id:\d+}', 'Trip', 'destroy')->middleware(['auth']);

$router->add('GET', 'trip/public/{public_url:\w+}', 'Trip', 'showPublic');
$router->add('GET', 'trip/{id:\d+}/publish', 'Trip', 'publish')
    ->middleware(['auth', 'trip-owner'])
    ->validate(['id' => ['exists:trips']])
    ->ajax();
$router->add('GET', 'trip/{id:\d+}/classify', 'Trip', 'classify')
    ->middleware(['auth', 'trip-owner'])
    ->validate(['id' => ['exists:trips']])
    ->ajax();

// TODO: Maybe POST?
$router->add('GET', 'trip/day/{id:\d+}/actions', 'Trip', 'actions')->ajax()->middleware(['auth']);

// TODO: Maybe POST?
$router->add('GET', 'action-types', 'Trip', 'actionTypes')->ajax();

$router->add('GET', 'users', 'User', 'index');
$router->add('GET', 'login', 'User', 'loginPage');
$router->add('GET', 'forgotten-password', 'User', 'forgottenPasswordPage');
$router->add('POST', 'login', 'User', 'login')->validate(['login_email' => ['email', 'maxLen:255']]);
$router->add('POST', 'register', 'User', 'register')
    ->validate([
        'register_email' => ['required', 'email', 'maxLen:255'],
        'register_password' => ['required'],
        'register_password_confirm' => ['required', 'matches:register_password']
    ]);
$router->add('GET', 'logout', 'User', 'logout');
$router->add('POST', 'forgotten-password', 'User', 'forgottenPassword');
$router->add('GET', 'profile', 'User', 'profile')->middleware(['auth']);
