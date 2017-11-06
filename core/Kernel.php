<?php

namespace Core;

class Kernel
{
    private $router;

    function __construct(\Core\Router $router) {
        $this->router = $router;
    }

    public function handle(Request $request) {
        if (!$this->router->match($request)) {
            // TODO: 404
            return;
        }

        // TODO: Layer middleware and request process together (onion)
        $request->process();
    }
}