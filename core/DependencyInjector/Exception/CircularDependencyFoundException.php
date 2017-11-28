<?php

namespace Core\DependencyInjector\Exception;

use Exception;

/**
 * Class CircularDependencyFoundException
 * @package Core\DependencyInjector\Exception
 * @author Martin Brom
 */
class CircularDependencyFoundException extends Exception
{
    /**
     * CircularDependencyFoundException constructor.
     * @param string $class
     */
    public function __construct($class) {
        $message = "Class " . $class . " has itself as a direct or an indirect dependency";
        parent::__construct($message);
    }
}
