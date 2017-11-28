<?php

namespace Core\Routing\Exception;

/**
 * Class RouteNameAlreadySetException
 * @package Core\Routing\Exception
 * @author Martin Brom
 */
class RouteNameAlreadySetException extends RouteArgumentAlreadySetException
{
    /**
     * RouteNameAlreadySetException constructor.
     */
    public function __construct() {
        parent::__construct('route name');
    }
}
