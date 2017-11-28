<?php

namespace Core\Routing\Exception;

use Exception;

/**
 * Class RouteNameAlreadyExistsException
 * @package Core\Routing\Exception
 * @author Martin Brom
 */
class RouteNameAlreadyExistsException extends Exception
{
    /**
     * RouteNameAlreadyExistsException constructor.
     * @param string $routeName
     */
    public function __construct($routeName) {
        $message = 'Route ' . $routeName . ' already exists';
        parent::__construct($message);
    }
}
