<?php

namespace Core\Routing\Exception;

class RouteAjaxStateAlreadySetException extends RouteArgumentAlreadySetException
{
    public function __construct() {
        parent::__construct('ajax state');
    }
}
