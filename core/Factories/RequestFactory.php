<?php

namespace Core\Factories;

use Core\Http\Request;

class RequestFactory implements Factory
{
    private $di;

    public function __construct() {
        $this->di = di();
    }

    public function make(): Request {
        return new Request($this->di);
    }
}