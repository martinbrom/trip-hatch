<?php

return [
    'login' => [
        'success' => 'You have been successfully logged in',
        'wrong'   => 'Username and password combination does not exist in our database'
    ],
    'logout' => [
        'success' => 'You have been successfully logged out'
    ],
    'publish' => [
        'success' => 'The trip has been successfully published',
        'error' => 'Something went wrong while trying to publish the trip'
    ],
    'classify' => [
        'success' => 'The trip has been successfully classified',
        'error' => 'Something went wrong while trying to classify the trip'
    ],
    'change-display-name' => [
        'success' => 'Your display name has been successfully changed',
        'error' => 'Something went wrong while trying to change a display name'
    ],
    'change-password' => [
        'success' => 'Your password has been successfully changed',
        'error' => 'Something went wrong while trying to change a password'
    ],
    'register' => [
        'success' => 'You have been successfully registered',
        'error' => 'Something went wrong while trying to register a new account'
    ],
    'trip' => [
        'missing' => 'Trip doesn\'t exist',
        'no-travellers' => 'There are currently no travellers on this trip',
        'no-days' => 'There are currently no days planned on this trip'
    ],
    'remove-user' => [
        'error' => 'Something went wrong while trying to remove a user from a trip',
        'success' => 'You have successfully removed a user from a trip',
        'wrong-role' => 'You can only remove travellers from a trip'
    ],
    'trip-create' => [
        'success' => 'A new trip has been successfully hatched',
        'error' => 'Something went wrong while trying to hatch a new trip'
    ],
    'trip-add-day' => [
        'error' => 'Something went wrong while trying to add a new day',
        'success' => 'A new day has been successfully added'
    ],
    'day' => [
        'missing' => 'Day doesn\'t exist'
    ],
    'day-edit' => [
        'error' => 'Something went wrong while trying to edit a day',
        'success' => 'A day has been successfully edited'
    ],
    'day-delete' => [
        'error' => 'Something went wrong while trying to delete a day',
        'success' => 'Day \':p1\' has been successfully deleted'
    ],
    'actions' => [
        'success' => 'Actions have been successfully loaded'
    ],
    'trip-add-action' => [
        'success' => 'New action has been successfully added',
        'error' => 'Something went wrong while trying to add a new action'
    ],
    'action' => [
        'missing' => 'Action doesn\'t exist'
    ],
    'trip-edit-action' => [
        'error' => 'Something went wrong while trying to edit an action',
        'success' => 'An action has been successfully edited'
    ],
    'trip-edit' => [
        'error' => 'Something went wrong while trying to edit a trip',
        'success' => 'A trip has been successfully edited'
    ],
    'trip-invite' => [
        'error' => 'Something went wrong while trying to invite a person to this trip',
        'success' => 'A person has been successfully invited to this trip',
        'exists' => 'This person has already been invited to this trip. You can invite again after 10 minutes',
        'missing' => 'Trip and token combination does not exist in our database'
    ],
    'trip-invite-accept' => [
        'error' => 'Something went wrong while trying to accept a trip invitation',
        'success' => 'You have successfully accepted a trip invitation',
        'access' => 'You already have access to this trip'
    ]
];
