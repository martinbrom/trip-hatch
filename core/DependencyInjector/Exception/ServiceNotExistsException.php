<?php

namespace Core\DependencyInjector\Exception;

use Exception;

class ServiceNotExistsException extends Exception
{
    public function __construct($service) {
        $message = 'Service ' . $service . ' doesn\'t exist';
        parent::__construct($message);
    }
}