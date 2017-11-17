<?php

namespace Core;

use Core\Factories\ResponseFactory;
use Core\Http\Request;
use Core\Http\Response\Response;
use Core\Middleware\MiddlewareHandler;
use Core\Routing\Router;

/**
 * Handles incoming request, tries to match request and route,
 * runs before and after middleware, processes request and returns
 * a response
 * @package Core
 * @author Martin Brom
 */
class Kernel
{
    /** @var Router Instance containing all application routes */
    private $router;

    /** @var MiddlewareHandler Instance for running middleware on request and response */
    private $middlewareHandler;

    /** @var ResponseFactory Instance for creating response instances */
    private $responseFactory;

    /**
     * Creates new instance and injects router and middleware handler
     * @param Router $router Instance containing all application routes
     * @param MiddlewareHandler $middlewareHandler Instance for running middleware
     *                                             on request and response
     * @param ResponseFactory $responseFactory Factory for creating responses
     */
    function __construct(Router $router, MiddlewareHandler $middlewareHandler, ResponseFactory $responseFactory) {
        $this->router = $router;
        $this->middlewareHandler = $middlewareHandler;
        $this->responseFactory = $responseFactory;
    }

    /**
     * Checks whether incoming request parameters match any
     * route, runs before middleware on it and the request is
     * then being processed if middleware passed
     * After obtaining a response from processing the request,
     * after middleware is run to modify the response and it
     * is then sent back to the client
     * @param Request $request Incoming request to check using
     *                         middleware and process afterwards
     * @return Response Either failed middleware response
     *                  or actual response
     */
    public function handle(Request $request): Response {
        if (!$this->router->match($request))
            return $this->responseFactory->error(404);

        $this->middlewareHandler->setRequest($request);
        if (!$this->middlewareHandler->runBefore())
            return $this->middlewareHandler->getResponse();

        $response = $request->process();
        $this->middlewareHandler->runAfter($response);

        return $response;
    }
}