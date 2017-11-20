<?php

if (!function_exists('bcrypt')) {
    function bcrypt($password) {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
    }
}

if (!function_exists('token')) {
    function token(int $length) {
        $characterSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        $max = 61;
        for ($i = 0; $i < $length; $i++)
            $token .= $characterSet[random_int(0, $max)];

        return $token;
    }
}

if (!function_exists('getallheaders')) {
    function getallheaders() {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }
}
