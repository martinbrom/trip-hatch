<?php

namespace Core\Factories;

use Core\DependencyInjector;
use Core\Http\Request;

class RequestFactory
{
    private $di;
    private $responseFactory;
    private $validatorFactory;

    public function __construct(ResponseFactory $responseFactory, ValidatorFactory $validatorFactory, DependencyInjector $di) {
        $this->di = $di;
        $this->responseFactory = $responseFactory;
        $this->validatorFactory = $validatorFactory;
    }

    public function make(): Request {
        return new Request($this->di, $this->responseFactory, $this->validatorFactory);
    }
}