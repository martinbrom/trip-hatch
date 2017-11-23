<?php

namespace Core\Routing\Exception;

use Exception;

class RouteNameAlreadyExistsException extends Exception
{
    public function __construct($routeName) {
        $message = 'Route ' . $routeName . ' already exists';
        parent::__construct($message);
    }
}
