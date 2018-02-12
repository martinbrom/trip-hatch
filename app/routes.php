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

// ------------ ADMIN -----------
$rb->add('GET', 'admin', 'Admin', 'index')
    ->middleware(['admin'])
    ->name('admin');
$rb->add('GET', 'admin/users', 'Admin', 'usersPage')
    ->middleware(['admin'])
    ->name('admin.users');
$rb->add('GET', 'admin/delete-user/{user_id:\d+}', 'Admin', 'deleteUser')
    ->middleware(['admin'])
    ->name('admin.delete-user');

// ---------- COMMENTS ----------
$rb->add('GET', 'trip/{trip_id:\d+}/comments', 'TripComments', 'index')
    ->middleware(['traveller'])
    ->name('trip.comments');
$rb->add('POST', 'trip/{trip_id:\d+}/comment/add', 'TripComments', 'create')
    ->middleware(['traveller'])
    ->validate(['comment_content' => ['required', 'maxLen:500']])
    ->name('trip.comments.create.submit');

// ------------ FILES -----------
$rb->add('GET', 'trip/{trip_id:\d+}/files', 'TripFiles', 'index')
    ->middleware(['traveller'])
    ->name('trip.files');
$rb->add('POST', 'trip/{trip_id:\d+}/file/add', 'TripFiles', 'create')
    ->middleware(['traveller'])
    ->validate([
        'trip_file_title' => ['required', 'maxLen:50'],
        'trip_file' => ['fileRequired', 'fileMaxSize:20971520', 'fileType:odt,txt,pdf,doc']
    ])
    ->name('trip.files.create.submit');

// ----------- INVITE -----------
$rb->add('GET', 'trip/{trip_id:\d+}/invite', 'Invite', 'invitePage')
    ->middleware(['organiser'])
    ->name('trip.invite');
$rb->add('POST', 'trip/{trip_id:\d+}/invite', 'Invite', 'invite')
    ->middleware(['organiser'])
    ->validate(['invite_email' => ['email', 'required', 'maxLen:255'], 'invite_message' => ['maxLen:255']])
    ->name('trip.invite.submit');
$rb->add('GET', 'trip/invite-accept/{token:\w+}', 'Invite', 'inviteAccept')
    ->middleware(['logged'])
    ->name('trip.invite-accept');

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
    ->middleware(['organiser'])
    ->validate(['trip_title' => ['required', 'maxLen:100']])
    ->name('trip.edit.submit');
$rb->add('GET', 'trip/public/{public_url:\w+}', 'Trip', 'showPublic')
    ->name('trip.public');
$rb->add('GET', 'trip/{trip_id:\d+}/manage-people', 'Trip', 'managePeoplePage')
    ->middleware(['organiser'])
    ->name('trip.manage-people');
$rb->add('GET', 'trip/{trip_id:\d+}/manage-staff', 'Trip', 'manageStaffPage')
    ->middleware(['owner'])
    ->name('trip.manage-staff');
$rb->add('GET', 'trip/{trip_id:\d+}/delete', 'Trip', 'delete')
    ->middleware(['owner'])
    ->name('trip.delete');


// ------------ USER ------------
$rb->add('GET', 'login', 'User', 'loginPage')
    ->name('login');
$rb->add('GET', 'forgotten-password', 'User', 'forgottenPasswordPage')
    ->name('forgotten-password');
$rb->add('POST', 'login', 'User', 'login')
    ->validate(['login_email' => ['email', 'maxLen:255'], 'login_password' => ['required']])
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
$rb->add('POST', 'forgotten-password', 'User', 'forgottenPassword')
    ->validate(['forgotten_password_email' => ['required', 'email', 'exists:users,email']])
    ->name('forgotten-password.submit');
$rb->add('GET', 'reset-password/{email:.+}/{token:\w+}', 'User', 'resetPasswordPage')
    ->name('reset-password');
$rb->add('POST', 'reset-password/{email:.+}/{token:\w+}', 'User', 'resetPassword')
    ->validate([
        'reset_password' => ['required'],
        'reset_password_confirm' => ['required', 'matches:reset_password']
    ])
    ->name('reset-password.submit');

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

// ----------- COMMENTS ---------
$rb->add('GET', 'trip/{trip_id:\d+}/comment/{comment_id:\d+}/delete', 'TripComments', 'delete')
    ->middleware(['organiser'])
    ->name('trip.comments.delete.submit')
    ->ajax();

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

// ------------ FILES -----------
$rb->add('GET', 'trip/{trip_id:\d+}/file/{file_id:\d+}/delete', 'TripFiles', 'delete')
    ->middleware(['organiser'])
    ->name('trip.files.delete')
    ->ajax();

// ------------ TRIP ------------
$rb->add('GET', 'trip/{trip_id:\d+}/publish', 'Trip', 'publish')
    ->middleware(['owner'])
    ->name('trip.publish')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/classify', 'Trip', 'classify')
    ->middleware(['owner'])
    ->name('trip.classify')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/user/{user_trip_id:\d+}/remove', 'Trip', 'removeUser')
    ->middleware(['organiser'])
    ->name('trip.user.remove')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/user/{user_trip_id:\d+}/promote', 'Trip', 'promoteUser')
    ->middleware(['owner'])
    ->name('trip.user.promote')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/user/{user_trip_id:\d+}/demote', 'Trip', 'demoteUser')
    ->middleware(['owner'])
    ->name('trip.user.demote')
    ->ajax();
$rb->add('GET', 'trip/{trip_id:\d+}/day/add', 'Trip', 'addDay')
    ->middleware(['organiser'])
    ->name('trip.add-day')
    ->ajax();

$rb->create();
