<?php

namespace Core\Middleware;

use Core\DependencyInjector\DependencyInjector;
use Core\Factories\ResponseFactory;
use Core\Http\Request;
use Core\Http\Response\Response;
use Core\Middleware\Exception\MiddlewareResponseNotCreatedException;

/**
 * Class MiddlewareHandler
 * @package Core\Middleware
 * @author Martin Brom
 */
class MiddlewareHandler
{
    /** */
    const NAMESPACE = 'App\Middleware\\';

    /** @var Middleware[] */
    private $middleware;

    /** @var Request */
    private $request;

    /** @var */
    private $response;

    /** @var DependencyInjector */
    private $di;

    /** @var ResponseFactory */
    private $responseFactory;

    // TODO: If user logged, check if his login is valid before each request
    /** @var array */
    private $alwaysUsedMiddleware = ['csrf', 'alerts', 'viewData'];

    /**
     * MiddlewareHandler constructor.
     * @param ResponseFactory $responseFactory
     * @param DependencyInjector $di
     */
    function __construct(ResponseFactory $responseFactory, DependencyInjector $di) {
        $this->di = $di;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @return mixed
     * @throws MiddlewareResponseNotCreatedException
     */
    public function getResponse() {
        if (!isset($this->response))
            throw new MiddlewareResponseNotCreatedException();

        return $this->response;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
        $this->createMiddlewareInstances();
    }

    /**
     * @return bool
     */
    public function runBefore(): bool {
        foreach ($this->middleware as $mw) {
            $mw->setRequest($this->request);
            $response = $mw->before();
            if ($response != null) {
                $this->response = $response;
                return false;
            }
        }

        return true;
    }

    /**
     * @param Response $response
     */
    public function runAfter(Response $response) {
        $this->response = $response;
        foreach ($this->middleware as $mw) {
            $mw->setResponse($this->response);
            $mw->after();
        }
    }

    /**
     *
     */
    public function createMiddlewareInstances() {
        $middleware = array_merge($this->alwaysUsedMiddleware, $this->request->getMiddleware());
        $aliases = require __DIR__ . '/../middleware.php';

        foreach ($middleware as $mw) {
            $this->middleware []= $this->di->getService($aliases[$mw]);
        }
    }
}