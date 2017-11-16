<?php

namespace Core\Factories;

use Core\Http\Request;

class RequestFactory implements Factory
{
    private $di;
    private $responseFactory;
    private $validatorFactory;

    public function __construct(ResponseFactory $responseFactory, ValidatorFactory $validatorFactory) {
        $this->di = di();
        $this->responseFactory = $responseFactory;
        $this->validatorFactory = $validatorFactory;
    }

    public function make($args = []): Request {
        return new Request($this->di, $this->responseFactory, $this->validatorFactory);
    }
}