<?php

namespace Core\Factories;

use Core\Http\Request;

class RequestFactory implements Factory
{
    private $di;

    public function __construct() {
        $this->di = di();
    }

    public function make($args = []): Request {
        return new Request($this->di);
    }
}