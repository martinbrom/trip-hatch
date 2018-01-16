<?php

namespace Core\Factories;

use Core\DependencyInjector\DependencyInjector;
use Core\Http\Request;
use Core\Session;

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

    /** @var Session */
    private $session;

    /**
     * RequestFactory constructor.
     * @param ResponseFactory $responseFactory
     * @param ValidatorFactory $validatorFactory
     * @param DependencyInjector $di
     * @param Session $session
     */
    public function __construct(
            ResponseFactory $responseFactory,
            ValidatorFactory $validatorFactory,
            DependencyInjector $di,
            Session $session) {
        $this->di = $di;
        $this->responseFactory = $responseFactory;
        $this->validatorFactory = $validatorFactory;
        $this->session = $session;
    }

    /**
     * @return Request
     */
    public function make(): Request {
        return new Request($this->di, $this->responseFactory, $this->validatorFactory, $this->session);
    }
}