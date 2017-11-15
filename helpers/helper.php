<?php

use Core\DependencyInjector;
use Core\Factories\ResponseFactory;

function di($service = null) {
    if ($service == null)
        return DependencyInjector::getInstance();

    return DependencyInjector::getInstance()->getService($service);
}

function bcrypt($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

function error($code) {
    return getResponseFactory()->error($code);
}

function redirect($location) {
    return getResponseFactory()->redirect($location);
}

function getResponseFactory(): ResponseFactory {
    return di(ResponseFactory::class);
}

function str_replace_first(string $haystack, string $replace, string $needle) {
    $pos = strpos($haystack, $needle);
    if ($pos !== false) $haystack = substr_replace($haystack, $replace, $pos, strlen($needle));
    return $haystack;
}

function str_replace_last(string $haystack, string $replace, string $needle) {
    $pos = strrpos($haystack, $needle);
    if ($pos !== false) $haystack = substr_replace($haystack, $replace, $pos, strlen($needle));
    return $haystack;
}
