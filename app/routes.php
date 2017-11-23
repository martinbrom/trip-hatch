<?php

$router = $di->getService(\Core\Routing\Router::class);

// ---------------------------------------
//  WEB ROUTES
// ---------------------------------------

// ----------- ACTION -----------
// -------- ACTION TYPES --------

// ------------ ADMIN -----------
$router->add('GET', 'admin', 'Admin', 'index')
    ->middleware(['admin']);

// ------------- DAY ------------

// ------------ HOME ------------
$router->add('GET', '', 'Home', 'index');
$router->add('GET', 'layout', 'Home', 'layout');
$router->add('GET', 'testvalidate', 'Home', 'testValidation')
    ->validate(['a' => ['between:10,100', 'email'], 'b' => ['max:30']]);
// TODO: FAQ, terms etc.

// ------------ TRIP ------------
$router->add('GET', 'trips', 'Trip', 'index')
    ->middleware(['logged']);
$router->add('GET', 'trip/create', 'Trip', 'create')
    ->middleware(['logged']);
$router->add('POST', 'trips', 'Trip', 'store')
    ->middleware(['logged'])
    ->validate(['title' => ['required', 'max:100']]);
$router->add('GET', 'trip/{id:\d+}', 'Trip', 'show')
    ->middleware(['logged']);
$router->add('GET', 'trip/{id:\d+}/edit', 'Trip', 'edit')
    ->middleware(['logged']);
$router->add('PUT', 'trip/{id:\d+}', 'Trip', 'update')
    ->middleware(['logged']);
$router->add('DELETE', 'trip/{id:\d+}', 'Trip', 'destroy')
    ->middleware(['logged']);
$router->add('GET', 'trip/public/{public_url:\w+}', 'Trip', 'showPublic');

// ------------ USER ------------
$router->add('GET', 'login', 'User', 'loginPage');
$router->add('GET', 'forgotten-password', 'User', 'forgottenPasswordPage');
$router->add('POST', 'login', 'User', 'login')
    ->validate(['login_email' => ['email', 'maxLen:255']]);
$router->add('POST', 'register', 'User', 'register')
    ->validate([
        'register_email' => ['required', 'email', 'maxLen:255'],
        'register_password' => ['required'],
        'register_password_confirm' => ['required', 'matches:register_password']
    ]);
$router->add('GET', 'logout', 'User', 'logout');
$router->add('POST', 'forgotten-password', 'User', 'forgottenPassword');

// -------- USER SETTINGS -------
$router->add('GET', 'profile', 'UserSettings', 'profile')
    ->middleware(['logged']);
$router->add('GET', 'change-display-name', 'UserSettings', 'changeDisplayNamePage')
    ->middleware(['logged']);
$router->add('POST', 'change-display-name', 'UserSettings', 'changeDisplayName')
    ->middleware(['logged']);
$router->add('GET', 'change-password', 'UserSettings', 'changePasswordPage')
    ->middleware(['logged']);
$router->add('POST', 'change-password', 'UserSettings', 'changePassword')
    ->middleware(['logged']);

// ---------------------------------------
//  AJAX ROUTES
// ---------------------------------------

// ----------- ACTION -----------
$router->add('GET', 'trip/day/{id:\d+}/actions', 'Action', 'actions')
    ->middleware(['logged'])
    ->ajax();

// -------- ACTION TYPES --------
$router->add('GET', 'action-types', 'ActionType', 'index')
    ->ajax();

// ------------ ADMIN -----------

// ------------- DAY ------------
$router->add('GET', 'trip/{id:\d+}/day/{day_id:\d+}/delete', 'Day', 'delete')
    ->middleware(['organiser'])
    ->ajax();

// ------------ HOME ------------

// ------------ TRIP ------------
$router->add('GET', 'trip/{id:\d+}/publish', 'Trip', 'publish')
    ->middleware(['owner'])
    ->ajax();
$router->add('GET', 'trip/{id:\d+}/classify', 'Trip', 'classify')
    ->middleware(['owner'])
    ->ajax();

// ------------ USER ------------
// -------- USER SETTINGS -------
