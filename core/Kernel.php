<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response\Response;
use Core\Routing\Router;

class Kernel
{
    const NAMESPACE = 'App\Middleware\\';
    private $router;
    private $di;
    private $alwaysUsedMiddleware = ['alerts', 'viewData'];

    function __construct(Router $router) {
        $this->router = $router;
        $this->di = di();
    }

    public function handle(Request $request): Response {
        if (!$this->router->match($request)) return error(404);

        $middlewareInstances = $this->createMiddlewareInstances($request);
        if ($this->runBefore($middlewareInstances, $request)) {
            $response = $request->process();
            $this->runAfter($middlewareInstances, $response);
            return $response;
        }

        // TODO: If run before failed, handle errors
        return redirect('/login');
    }

    /**
     * @param Middleware[] $middleware Instances of middleware to be run
     * @param Request $request
     * @return bool True if before middleware ran without error
     */
    public function runBefore($middleware, Request $request) {

        // TODO: Maybe throw exceptions???
        foreach ($middleware as $mw) {
            $mw->setRequest($request);

            if (!$mw->before()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Middleware[] $middleware Instances of middleware to be run
     * @param Response $response
     */
    public function runAfter($middleware, Response $response) {
        foreach ($middleware as $mw) {
            $mw->setResponse($response);
            $mw->after();
        }
    }

    /**
     * @param Request $request
     * @return Middleware[] Instances of created Middleware
     */
    public function createMiddlewareInstances(Request $request) {
        $middleware = array_merge($this->alwaysUsedMiddleware, $request->getMiddleware());
        $middlewareInstances = [];

        $aliases = require '../app/Middleware/middleware.php';

        foreach ($middleware as $mw) {
            $middlewareInstance = $this->di->getService($aliases[$mw]);
            $middlewareInstance->setRequest($request);
            $middlewareInstances []= $middlewareInstance;
        }

        return $middlewareInstances;
    }
}