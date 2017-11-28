<?php

namespace Core\Routing\Exception;

use Exception;

/**
 * Class RouteArgumentAlreadySetException
 * @package Core\Routing\Exception
 * @author Martin Brom
 */
class RouteArgumentAlreadySetException extends Exception
{
    /**
     * RouteArgumentAlreadySetException constructor.
     * @param string $argumentType
     */
    public function __construct($argumentType) {
        $message = 'Route argument: ' . $argumentType . ' was already set';
        parent::__construct($message);
    }
}
