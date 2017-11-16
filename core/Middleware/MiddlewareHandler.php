<?php

namespace Core\Middleware;

use Core\Factories\ResponseFactory;
use Core\Http\Request;
use Core\Http\Response\Response;
use Core\Middleware\Exception\MiddlewareResponseNotCreatedException;

class MiddlewareHandler
{
    const NAMESPACE = 'App\Middleware\\';

    /** @var Middleware[] */
    private $middleware;

    /** @var Request */
    private $request;
    private $response;
    private $di;
    private $responseFactory;

    // TODO: Add csrf here after it's finished
    private $alwaysUsedMiddleware = ['csrf', 'alerts', 'viewData'];

    function __construct(ResponseFactory $responseFactory) {
        $this->di = di();
        $this->responseFactory = $responseFactory;
    }

    public function getResponse() {
        if (!isset($this->response))
            throw new MiddlewareResponseNotCreatedException();

        return $this->response;
    }

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

    public function createMiddlewareInstances() {
        $middleware = array_merge($this->alwaysUsedMiddleware, $this->request->getMiddleware());
        $aliases = require '../core/middleware.php';

        foreach ($middleware as $mw) {
            $this->middleware []= $this->di->getService($aliases[$mw]);
        }
    }
}