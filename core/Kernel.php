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
    private $alwaysUsedMiddleware = ['alerts'];

    function __construct(Router $router) {
        $this->router = $router;
        $this->di = di();
    }

    public function handle(Request $request): Response {
        if (!$this->router->match($request)) return error(404);

        $middlewareInstances = $this->createMiddlewareInstances($request->getMiddleware());
        if ($this->runBefore($middlewareInstances)) {
            $response = $request->process();
            $this->runAfter($middlewareInstances);
            return $response;
        }

        // TODO: If run before failed, handle errors
        return redirect('/login');
    }

    /**
     * @param Middleware[] $middleware Instances of middleware to be run
     * @return bool True if before middleware ran without error
     */
    public function runBefore($middleware) {

        // TODO: Maybe throw exceptions???
        foreach ($middleware as $mw) {
            if (!$mw->before()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param Middleware[] $middleware Instances of middleware to be run
     */
    public function runAfter($middleware) {
        foreach ($middleware as $mw) {
            $mw->after();
        }
    }

    /**
     * @param $middleware
     * @return Middleware[] Instances of created Middleware
     */
    public function createMiddlewareInstances($middleware) {
        $middleware = array_merge($this->alwaysUsedMiddleware, $middleware);
        $middlewareInstances = [];

        $aliases = require '../app/Middleware/middleware.php';

        foreach ($middleware as $mw) {
            $middlewareInstances []= $this->di->getService($aliases[$mw]);
        }

        return $middlewareInstances;
    }
}