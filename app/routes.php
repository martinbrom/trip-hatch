<?php

$router = $di->getService(\Core\Routing\Router::class);

$router->add('GET', '', 'Home', 'index');
$router->add('GET', 'layout', 'Home', 'layout');
$router->add('GET', 'testvalidate', 'Home', 'testValidation')->validate(['a' => ['between:10,100', 'email'], 'b' => ['max:30']]);
// TODO: FAQ, terms etc.

$router->add('GET', 'trips', 'Trip', 'index')->middleware(['logged']);
$router->add('GET', 'trip/create', 'Trip', 'create')->middleware(['logged']);
$router->add('POST', 'trips', 'Trip', 'store')->middleware(['logged'])->validate(['title' => ['required', 'max:100']]);
$router->add('GET', 'trip/{id:\d+}', 'Trip', 'show')->middleware(['logged']);
$router->add('GET', 'trip/{id:\d+}/edit', 'Trip', 'edit')->middleware(['logged']);
$router->add('PUT', 'trip/{id:\d+}', 'Trip', 'update')->middleware(['logged']);
$router->add('DELETE', 'trip/{id:\d+}', 'Trip', 'destroy')->middleware(['logged']);

// TODO: Redirect to normal trip page if logged and accessing public url
$router->add('GET', 'trip/public/{public_url:\w+}', 'Trip', 'showPublic');
$router->add('GET', 'trip/{id:\d+}/publish', 'Trip', 'publish')
    ->middleware(['owner'])
    ->ajax();

$router->add('GET', 'trip/{id:\d+}/classify', 'Trip', 'classify')
    ->middleware(['owner'])
    ->ajax();

$router->add('GET', 'trip/day/{id:\d+}/actions', 'Action', 'actions')->ajax()->middleware(['logged']);

$router->add('GET', 'action-types', 'ActionType', 'index')->ajax();

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
$router->add('GET', 'profile', 'User', 'profile')->middleware(['logged']);
