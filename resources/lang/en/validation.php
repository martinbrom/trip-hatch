<?php

return [
    'login_email' => [
        'email' => 'Login email must be a valid email address',
        'maxLen' => 'Login email must be at most :p1 characters long'
    ],
    'login_password' => [
        'required' => 'Login password is required'
    ],
    'register_email' => [
        'required' => 'Registration email is required',
        'email' => 'Registration email must be a valid email address',
        'maxLen' => 'Registration email must be at most :p1 characters long',
        'unique' => 'This registration email is already used'
    ],
    'register_password' => [
        'required' => 'Registration password is required'
    ],
    'register_password_confirm' => [
        'required' => 'Confirmation of registration password is required',
        'matches' => 'Confirmation of registration password must match registration password'
    ],
    'trip_title' => [
        'required' => 'Trip title is required',
        'maxLen' => 'Trip title must be at most :p1 characters long'
    ],
    'day_title' => [
        'required' => 'Day title is required',
        'maxLen' => 'Day title must be at most :p1 characters long'
    ],
    'action_content' => [
        'required' => 'Action content is required',
        'maxLen' => 'Action content must be at most :p1 characters long'
    ],
    'action_title' => [
        'required' => 'Action title is required',
        'maxLen' => 'Action title must be at most :p1 characters long'
    ],
    'action_type' => [
        'required' => 'Action type is required',
        'int' => 'Action type must be a number',
        'exists' => 'Action type must be a valid action type'
    ],
    'action_edit_content' => [
        'required' => 'Action content is required',
        'maxLen' => 'Action content must be at most :p1 characters long'
    ],
    'action_edit_title' => [
        'required' => 'Action title is required',
        'maxLen' => 'Action title must be at most :p1 characters long'
    ],
    'action_edit_type' => [
        'required' => 'Action type is required',
        'int' => 'Action type must be a number',
        'exists' => 'Action type must be a valid action type'
    ],
    'display_name' => [
        'maxLen' => 'Display name must be at most :p1 characters long'
    ],
    'old_password' => [
        'required' => 'Old password is required',
        'passwordVerify' => 'Old password doesn\'t match your current password'
    ],
    'new_password' => [
        'required' => 'New password is required'
    ],
    'new_password_confirm' => [
        'required' => 'Confirmation of new password is required',
        'matches' => 'Confirmation of new password must match new password'
    ],
    'invite_email' => [
        'required' => 'Invitation email is required',
        'email' => 'Invitation email must be a valid email address',
        'maxLen' => 'Invitation email must be at most :p1 characters long'
    ],
    'invite_message' => [
        'maxLen' => 'Invitation message must be at most :p1 characters long'
    ],
    'file' => [
        'max_filesize' => 'The uploaded file is too large',
        'partial' => 'The file was only partially uploaded',
        'write' => 'The uploaded file couldn\'t be written on the disk',
        'default' => 'The file couldn\'t be uploaded for some reason',
        'extension' => 'The uploaded file is not in an allowed format. Allowed formats are: JPG and PNG'
    ]
];
