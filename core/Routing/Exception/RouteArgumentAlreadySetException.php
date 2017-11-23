<?php

namespace Core\Routing\Exception;

use Exception;

class RouteArgumentAlreadySetException extends Exception
{
    public function __construct($argumentType) {
        $message = 'Route argument: ' . $argumentType . ' was already set';
        parent::__construct($message);
    }
}
