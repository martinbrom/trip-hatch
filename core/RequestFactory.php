<?php

namespace Core;

class RequestFactory
{
    private $di;

    public function __construct() {
        $this->di = di();
    }

    public function make(): Request {
        return new Request($this->di);
    }
}