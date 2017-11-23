<?php

namespace Core\Routing\Exception;

class RouteMiddlewareAlreadySetException extends RouteArgumentAlreadySetException
{
    public function __construct() {
        parent::__construct('middleware');
    }
}
