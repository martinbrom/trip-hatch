<?php

namespace App\Exception;

use Exception;

class ControllerNotFoundException extends Exception
{
    public function __construct($controller) {
        $message = "Controller " . $controller . " not found";
        parent::__construct($message);
    }
}