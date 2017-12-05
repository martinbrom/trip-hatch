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
$rb->add('GET', 'admin/users', 'Admin', 'usersPage')
    ->middleware(['admin'])
    ->name('admin.users');
$rb->add('GET', 'admin/trips', 'Admin', 'tripsPage')
    ->middleware(['admin'])
    ->name('admin.trips');

// ------------- DAY ------------

// ------------ HOME ------------
$rb->add('GET', '', 'Home', 'index');
$rb->add('GET', 'testvalidate', 'Home', 'testValidation')
    ->validate(['a' => ['between:10,100', 'email'], 'b' => ['max:30']]);
// TODO: FAQ, terms etc.

// ------------ TRIP ------------
$rb->add('GET', 'trips', 'Trip', 'index')
    ->middleware(['logged'])
    ->name('dashboard');
$rb->add('GET', 'trip/create', 'Trip', 'createPage')
    ->middleware(['logged'])
    ->name('trip.create');
$rb->add('POST', 'trips', 'Trip', 'create')
    ->middleware(['logged'])
    ->validate(['trip_title' => ['required', 'maxLen:100']])
    ->name('trip.create.submit');
$rb->add('GET', 'trip/{trip_id:\d+}', 'Trip', 'show')
    ->middleware(['traveller'])
    ->name('trip.show');
$rb->add('GET', 'trip/{trip_id:\d+}/edit', 'Trip', 'editPage')
    ->middleware(['organiser'])
    ->name('trip.edit');
$rb->add('POST', 'trip/{trip_id:\d+}/edit', 'Trip', 'edit')
    ->middleware(['organiser']);
$rb->add('GET', 'trip/public/{public_url:\w+}', 'Trip', 'showPublic')
    ->name('trip.public');
$rb->add('GET', 'trip/{trip_id:\d+}/manage-people', 'Trip', 'managePeoplePage')
    ->middleware(['organiser'])
    ->name('trip.manage-people');
$rb->add('GET', 'trip/{trip_id:\d+}/manage-staff', 'Trip', 'manageStaffPage')
    ->middleware(['owner'])
    ->name('trip.manage-staff');
$rb->add('GET', 'trip/{trip_id:\d+}/invite', 'Trip', 'invitePage')
    ->middleware(['organiser'])
    ->name('trip.invite');
$rb->add('POST', 'trip/{trip_id:\d+}/invite', 'Trip', 'invite')
    ->middleware(['organiser'])
    ->name('trip.invite.submit');

// ------------ USER ------------
$rb->add('GET', 'login', 'User', 'loginPage')
    ->name('login');
$rb->add('GET', 'forgotten-password', 'User', 'forgottenPasswordPage')
    ->name('forgotten-password');
$rb->add('POST', 'login', 'User', 'login')
    ->validate(['login_email' => ['email', 'maxLen:255']])
    ->name('login.submit');
$rb->add('POST', 'register', 'User', 'register')
    ->validate([
        'register_email' => ['required', 'email', 'maxLen:255', 'unique:users,email'],
        'register_password' => ['required'],
        'register_password_confirm' => ['required', 'matches:register_password']
    ])
    ->name('register.submit');
$rb->add('GET', 'logout', 'User', 'logout')
    ->name('logout');
$rb->add('POST', 'forgotten-password', 'User', 'forgottenPassword');

// -------- USER SETTINGS -------
$rb->add('GET', 'profile', 'UserSettings', 'profile')
    ->middleware(['logged'])
    ->name('profile');
$rb->add('GET', 'settings/change-display-name', 'UserSettings', 'changeDisplayNamePage')
    ->middleware(['logged'])
    ->name('user.settings.change-display-name');
$rb->add('POST', 'settings/change-display-name', 'UserSettings', 'changeDisplayName')
    ->validate([
        'display_name' => ['maxLen:30']
    ])
    ->name('user.settings.change-display-name.submit')
    ->middleware(['logged']);
$rb->add('GET', 'settings/change-password', 'UserSettings', 'changePasswordPage')
    ->middleware(['logged'])
    ->name('user.settings.change-password');
$rb->add('POST', 'settings/change-password', 'UserSettings', 'changePassword')
    ->middleware(['logged'])
    ->validate([
        'old_password' => ['required', 'passwordVerify'],
        'new_password' => ['required'],
        'new_password_confirm' => ['required', 'matches:new_password']
    ])
    ->name('user.settings.change-password.submit');

// ---------------------------------------
//  AJAX ROUTES
// ---------------------------------------

// ----------- ACTION -----------
$rb->add('GET', 'trip/{trip_id:\d+}/day/{day_id:\d+}/actions', 'Action', 'actions')
    ->middleware(['logged'])
    ->name('trip.day.actions')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/day/{day_id:\d+}/action/add', 'Action', 'addActionModal')
    ->middleware(['logged'])
    ->name('trip.day.action.add')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/day/{day_id:\d+}/action/{action_id:\d+}/edit', 'Action', 'editModal')
    ->middleware(['organiser'])
    ->name('trip.day.action.edit')
    ->ajax();
$rb->add('POST', 'trip/{trip_id:\d+}/day/{day_id:\d+}/action/{action_id:\d+}/edit', 'Action', 'edit')
    ->middleware(['organiser'])
    ->name('trip.day.action.edit.submit')
    ->validate([
        'action_edit_content' => ['required', 'maxLen:1000'],
        'action_edit_title' => ['required', 'maxLen:100'],
        'action_edit_type' => ['required', 'int', 'exists:action_types,id']
    ])
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/day/{day_id:\d+}/action/{action_id:\d+}/delete', 'Action', 'delete')
    ->middleware(['organiser'])
    ->name('trip.day.action.delete')
    ->ajax();

// -------- ACTION TYPES --------
$rb->add('GET', 'action-types', 'ActionType', 'index')
    ->ajax();

// ------------ ADMIN -----------

// ------------- DAY ------------
$rb->add('GET', 'trip/{trip_id:\d+}/day/{day_id:\d+}/delete', 'Day', 'delete')
    ->middleware(['organiser'])
    ->name('trip.day.delete')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/day/{day_id:\d+}/edit', 'Day', 'editModal')
    ->middleware(['organiser'])
    ->name('trip.day.edit')
    ->ajax();
$rb->add('POST', 'trip/{trip_id:\d+}/day/{day_id:\d+}/edit', 'Day', 'edit')
    ->middleware(['organiser'])
    ->name('trip.day.edit.submit')
    ->validate([
        'day_title' => ['required', 'maxLen:100']
        // TODO: Validate day_image
    ])
    ->ajax();
$rb->add('POST', 'trip/{trip_id:\d+}/day/{day_id:\d+}/action/add', 'Day', 'addAction')
    ->middleware(['logged'])
    ->name('trip.day.action.add.submit')
    ->validate([
        'action_content' => ['required', 'maxLen:1000'],
        'action_title' => ['required', 'maxLen:100'],
        'action_type' => ['required', 'int', 'exists:action_types,id']
    ])
    ->ajax();

// ------------ HOME ------------

// ------------ TRIP ------------
$rb->add('GET', 'trip/{trip_id:\d+}/publish', 'Trip', 'publish')
    ->middleware(['owner'])
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/classify', 'Trip', 'classify')
    ->middleware(['owner'])
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/user/{user_trip_id:\d+}/remove', 'Trip', 'removeUser')
    ->middleware(['organiser'])
    ->name('trip.user.remove')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/user/{user_trip_id:\d+}/promote', 'Trip', 'promoteUser')
    ->middleware(['owner'])
    ->name('trip.user.promote')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/day/add', 'Trip', 'addDay')
    ->middleware(['organiser'])
    ->name('trip.add-day')
    ->ajax();

// ------------ USER ------------
// -------- USER SETTINGS -------

$rb->create();
