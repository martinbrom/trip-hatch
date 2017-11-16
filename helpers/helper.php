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

function error($code, $data = []) {
    return getResponseFactory()->error($code, $data);
}

function redirect($location) {
    return getResponseFactory()->redirect($location);
}

function getResponseFactory(): ResponseFactory {
    return di(ResponseFactory::class);
}
