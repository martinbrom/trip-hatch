<?php

namespace Core\DependencyInjector\Exception;

use Exception;

/**
 * Class DependencyTypeNotFoundException
 * @package Core\DependencyInjector\Exception
 * @author Martin Brom
 */
class DependencyTypeNotFoundException extends Exception
{
    /**
     * DependencyTypeNotFoundException constructor.
     * @param string $className
     */
    public function __construct($className) {
        $message = 'No default value or type supplied for class ' . $className;
        parent::__construct($message);
    }
}