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
        'success' => 'Your display name has been successfully changed'
    ],
    'register' => [
        'success' => 'You have been successfully registered',
        'error' => 'Something went wrong while trying to register a new account'
    ],
    'trip' => [
        'missing' => 'Trip doesn\'t exist',
        'no-users' => 'There are currently no travellers on this trip'
    ],
    'remove-user' => [
        'error' => 'Something went wrong while trying to remove a user from a trip',
        'success' => 'You have successfully removed a user from a trip',
        'wrong-role' => 'You can only remove travellers from a trip'
    ]
];
