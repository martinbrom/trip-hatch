<?php

namespace Core\Routing\Exception;

/**
 * Class RouteMiddlewareAlreadySetException
 * @package Core\Routing\Exception
 * @author Martin Brom
 */
class RouteMiddlewareAlreadySetException extends RouteArgumentAlreadySetException
{
    /**
     * RouteMiddlewareAlreadySetException constructor.
     */
    public function __construct() {
        parent::__construct('middleware');
    }
}
