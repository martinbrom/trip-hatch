<?php

namespace Core;

class Kernel
{
    private $router;
    private $alwaysUsedMiddleware = [
        // 'AlwaysLoadedMiddleware'
    ];

    function __construct(\Core\Router $router) {
        $this->router = $router;
    }

    public function handle(Request $request) {
        if (!$this->router->match($request)) {
            // TODO: 404
            echo 404;
            return;
        }

        $middlewareInstances = $this->createMiddlewareInstances($request->getMiddleware());

        if ($this->runBefore($middlewareInstances)) {
            $response = $request->process();
            $this->runAfter($middlewareInstances);
            // View::render($response);
        }

        // TODO: If run before failed, handle errors
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

        foreach ($middleware as $mw) {
            $name = '\Core\\' . $mw;
            $middlewareInstances []= new $name;
        }

        return $middlewareInstances;
    }
}