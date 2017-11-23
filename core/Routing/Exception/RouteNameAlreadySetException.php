<?php

namespace Core\Routing\Exception;

class RouteNameAlreadySetException extends RouteArgumentAlreadySetException
{
    public function __construct() {
        parent::__construct('route name');
    }
}
