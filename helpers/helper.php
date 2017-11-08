<?php

use Core\DependencyInjector;

function di($service = null) {
    if ($service == null)
        return DependencyInjector::getInstance();

    return DependencyInjector::getInstance()->getService($service);
}
