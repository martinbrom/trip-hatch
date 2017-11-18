<?php

function bcrypt($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

function token(int $length) {
    $characterSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    $max = 61;
    for ($i = 0; $i < $length; $i++)
        $token .= $characterSet[random_int(0, $max)];

    return $token;
}
