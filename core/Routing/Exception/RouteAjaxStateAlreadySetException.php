<?php

namespace Core\Routing\Exception;

/**
 * Class RouteAjaxStateAlreadySetException
 * @package Core\Routing\Exception
 * @author Martin Brom
 */
class RouteAjaxStateAlreadySetException extends RouteArgumentAlreadySetException
{
    /**
     * RouteAjaxStateAlreadySetException constructor.
     */
    public function __construct() {
        parent::__construct('ajax state');
    }
}
