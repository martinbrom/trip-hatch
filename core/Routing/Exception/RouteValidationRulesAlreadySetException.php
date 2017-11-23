<?php

namespace Core\Routing\Exception;

class RouteValidationRulesAlreadySetException extends RouteArgumentAlreadySetException
{
    public function __construct() {
        parent::__construct('validation rules');
    }
}
