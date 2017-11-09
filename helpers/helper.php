<?php

use Core\DependencyInjector;

function di($service = null) {
    if ($service == null)
        return DependencyInjector::getInstance();

    return DependencyInjector::getInstance()->getService($service);
}

function bcrypt($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}
