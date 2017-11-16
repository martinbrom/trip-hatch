<?php

return [
    'login_email' => [
        'email' => 'Login email must be a valid email address',
        'maxLen' => 'Login email must be at most :p1 characters long'
    ],
    'register_email' => [
        'required' => 'Registration email is required',
        'email' => 'Registration email must be a valid email address',
        'maxLen' => 'Registration email must be at most :p1 characters long'
    ],
    'register_password' => [
        'required' => 'Registration password is required'
    ],
    'register_password_confirm' => [
        'required' => 'Confirmation of registration password is required',
        'matches' => 'Confirmation of registration password must match registration password'
    ]
];
