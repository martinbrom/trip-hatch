<?php

namespace Core\DependencyInjector\Exception;

use Exception;

/**
 * Class ServiceNotExistsException
 * @package Core\DependencyInjector\Exception
 * @author Martin Brom
 */
class ServiceNotExistsException extends Exception
{
    /**
     * ServiceNotExistsException constructor.
     * @param string $service
     */
    public function __construct($service) {
        $message = 'Service ' . $service . ' doesn\'t exist';
        parent::__construct($message);
    }
}