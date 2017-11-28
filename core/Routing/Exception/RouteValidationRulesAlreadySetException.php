<?php

namespace Core\Routing\Exception;

/**
 * Class RouteValidationRulesAlreadySetException
 * @package Core\Routing\Exception
 * @author Martin Brom
 */
class RouteValidationRulesAlreadySetException extends RouteArgumentAlreadySetException
{
    /**
     * RouteValidationRulesAlreadySetException constructor.
     */
    public function __construct() {
        parent::__construct('validation rules');
    }
}
