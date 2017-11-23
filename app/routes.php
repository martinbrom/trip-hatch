<?php

// ------------------------------------------------------
//  IT IS ESSENTIAL THAT YOU CALL ADD METHOD FIRST
//  OTHERWISE YOU COULD ACCESS PREVIOUS ROUTE
//  AND APPLICATION COULD BEHAVE UNEXPECTEDLY
//
//  YOU SHOULD ALSO CALL ROUTE BUILDER -> CREATE
//  AS A LAST LINE IN THIS FILE, ANY ROUTES DEFINED
//  AFTER IT WILL NOT BE CREATED
// ------------------------------------------------------

/** @var \Core\Routing\RouteBuilder $rb */
$rb = $di->getService(Core\Routing\RouteBuilder::class);

// ---------------------------------------
//  WEB ROUTES
// ---------------------------------------

// ----------- ACTION -----------
// -------- ACTION TYPES --------

// ------------ ADMIN -----------
$rb->add('GET', 'admin', 'Admin', 'index')
    ->middleware(['admin'])
    ->name('admin');

// ------------- DAY ------------

// ------------ HOME ------------
$rb->add('GET', '', 'Home', 'index');
$rb->add('GET', 'layout', 'Home', 'layout');
$rb->add('GET', 'testvalidate', 'Home', 'testValidation')
    ->validate(['a' => ['between:10,100', 'email'], 'b' => ['max:30']]);
// TODO: FAQ, terms etc.

// ------------ TRIP ------------
$rb->add('GET', 'trips', 'Trip', 'index')
    ->middleware(['logged'])
    ->name('dashboard');
$rb->add('GET', 'trip/create', 'Trip', 'create')
    ->middleware(['logged']);
$rb->add('POST', 'trips', 'Trip', 'store')
    ->middleware(['logged'])
    ->validate(['title' => ['required', 'max:100']]);
$rb->add('GET', 'trip/{id:\d+}', 'Trip', 'show')
    ->middleware(['logged'])
    ->name('trip-show');
$rb->add('GET', 'trip/{id:\d+}/edit', 'Trip', 'edit')
    ->middleware(['logged']);
$rb->add('PUT', 'trip/{id:\d+}', 'Trip', 'update')
    ->middleware(['logged']);
$rb->add('DELETE', 'trip/{id:\d+}', 'Trip', 'destroy')
    ->middleware(['logged']);
$rb->add('GET', 'trip/public/{public_url:\w+}', 'Trip', 'showPublic');

// ------------ USER ------------
$rb->add('GET', 'login', 'User', 'loginPage');
$rb->add('GET', 'forgotten-password', 'User', 'forgottenPasswordPage')
    ->name('login');
$rb->add('POST', 'login', 'User', 'login')
    ->validate(['login_email' => ['email', 'maxLen:255']]);
$rb->add('POST', 'register', 'User', 'register')
    ->validate([
        'register_email' => ['required', 'email', 'maxLen:255'],
        'register_password' => ['required'],
        'register_password_confirm' => ['required', 'matches:register_password']
    ]);
$rb->add('GET', 'logout', 'User', 'logout')
    ->name('logout');
$rb->add('POST', 'forgotten-password', 'User', 'forgottenPassword')
    ->name('forgotten-password');

// -------- USER SETTINGS -------
$rb->add('GET', 'profile', 'UserSettings', 'profile')
    ->middleware(['logged'])
    ->name('profile');
$rb->add('GET', 'change-display-name', 'UserSettings', 'changeDisplayNamePage')
    ->middleware(['logged']);
$rb->add('POST', 'change-display-name', 'UserSettings', 'changeDisplayName')
    ->middleware(['logged']);
$rb->add('GET', 'change-password', 'UserSettings', 'changePasswordPage')
    ->middleware(['logged']);
$rb->add('POST', 'change-password', 'UserSettings', 'changePassword')
    ->middleware(['logged']);

// ---------------------------------------
//  AJAX ROUTES
// ---------------------------------------

// ----------- ACTION -----------
$rb->add('GET', 'trip/day/{id:\d+}/actions', 'Action', 'actions')
    ->middleware(['logged'])
    ->ajax();

// -------- ACTION TYPES --------
$rb->add('GET', 'action-types', 'ActionType', 'index')
    ->ajax();

// ------------ ADMIN -----------

// ------------- DAY ------------
$rb->add('GET', 'trip/{id:\d+}/day/{day_id:\d+}/delete', 'Day', 'delete')
    ->middleware(['organiser'])
    ->ajax();

// ------------ HOME ------------

// ------------ TRIP ------------
$rb->add('GET', 'trip/{id:\d+}/publish', 'Trip', 'publish')
    ->middleware(['owner'])
    ->ajax();
$rb->add('GET', 'trip/{id:\d+}/classify', 'Trip', 'classify')
    ->middleware(['owner'])
    ->ajax();

// ------------ USER ------------
// -------- USER SETTINGS -------

$rb->create();
