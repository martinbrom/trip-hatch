<?php

namespace Core\Routing;

use Core\Routing\Exception\RouteNameAlreadyExistsException;

// TODO: Exceptions for setting rules, middleware, name etc a second time
/**
 * Class RouteBuilder
 * @package Core\Routing
 * @author Martin Brom
 */
class RouteBuilder
{
    /** @var Router */
    private $router;

    /** @var int */
    private $firstFreeRouteID;

    /** @var Route */
    private $currentRoute;

    /** @var Route[] */
    private $routes;

    function __construct(Router $router) {
        $this->router = $router;
        $this->firstFreeRouteID = 0;
    }

    public function create() {
        $this->router->setRoutes($this->routes);
    }

    public function add($method, $url, $controller, $action): self {
        $this->currentRoute = new Route($url, $controller, $action, $method);
        $this->routes[$this->firstFreeRouteID] = $this->currentRoute;
        $this->firstFreeRouteID++;
        return $this;
    }

    public function middleware($middleware): self {
        $this->currentRoute->setMiddleware($middleware);
        return $this;
    }

    public function ajax(): self {
        $this->currentRoute->setAjaxOnly(true);
        return $this;
    }

    public function validate($rules): self {
        $this->currentRoute->setValidationRules($rules);
        return $this;
    }

    public function name($name): self {
        if (isset($this->routes[$name]))
            throw new RouteNameAlreadyExistsException($name);

        $this->firstFreeRouteID--;
        unset($this->routes[$this->firstFreeRouteID]);
        $this->routes[$name] = $this->currentRoute;
        return $this;
    }
}