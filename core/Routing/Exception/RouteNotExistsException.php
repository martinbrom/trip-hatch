<?php

namespace Core\Routing\Exception;

use Exception;

class RouteNotExistsException extends Exception
{
    public function __construct($routeName) {
        $message = 'Route ' . $routeName . ' doesn\'t exist';
        parent::__construct($message);
    }
}
