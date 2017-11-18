<?php

namespace Core\DependencyInjector\Exception;

use Exception;

class DependencyTypeNotFoundException extends Exception
{
    public function __construct($className) {
        $message = 'No default value or type supplied for class ' . $className;
        parent::__construct($message);
    }
}