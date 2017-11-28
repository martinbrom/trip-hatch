<?php

namespace Core\Routing\Exception;

use Exception;

/**
 * Class RouteNotExistsException
 * @package Core\Routing\Exception
 * @author Martin Brom
 */
class RouteNotExistsException extends Exception
{
    /**
     * RouteNotExistsException constructor.
     * @param string $routeName
     */
    public function __construct($routeName) {
        $message = 'Route ' . $routeName . ' doesn\'t exist';
        parent::__construct($message);
    }
}
