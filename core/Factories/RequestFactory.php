<?php

namespace Core\Factories;

use Core\Http\Request;

class RequestFactory implements Factory
{
    private $di;
    private $responseFactory;

    public function __construct(ResponseFactory $responseFactory) {
        $this->di = di();
        $this->responseFactory = $responseFactory;
    }

    public function make($args = []): Request {
        return new Request($this->di, $this->responseFactory);
    }
}