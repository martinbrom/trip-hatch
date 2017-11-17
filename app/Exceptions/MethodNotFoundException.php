<?php

namespace App\Exception;

use Exception;

class MethodNotFoundException extends Exception
{
    public function __construct($controller, $method) {
        $message = "Method " . $method . " not found on controller " . $controller;
        parent::__construct($message);
    }
}