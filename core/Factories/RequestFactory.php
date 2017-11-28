<?php

namespace Core\Factories;

use Core\DependencyInjector\DependencyInjector;
use Core\Http\Request;

/**
 * Class RequestFactory
 * @package Core\Factories
 * @author Martin Brom
 */
class RequestFactory
{
    /** @var DependencyInjector */
    private $di;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var ValidatorFactory */
    private $validatorFactory;

    /**
     * RequestFactory constructor.
     * @param ResponseFactory $responseFactory
     * @param ValidatorFactory $validatorFactory
     * @param DependencyInjector $di
     */
    public function __construct(ResponseFactory $responseFactory, ValidatorFactory $validatorFactory, DependencyInjector $di) {
        $this->di = $di;
        $this->responseFactory = $responseFactory;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * @return Request
     */
    public function make(): Request {
        return new Request($this->di, $this->responseFactory, $this->validatorFactory);
    }
}