<?php

namespace Core\Routing;

use Core\Http\Request;
use Core\Routing\Exception\RouteNotExistsException;

/**
 * Class Router
 * @package Core\Routing
 * @author Martin Brom
 */
class Router
{
    /** @var Route[] List of registered routes */
    protected $routes = [];

    /**
     * @param $name
     * @return Route
     * @throws RouteNotExistsException
     */
    public function getRoute($name) {
        if (!isset($this->routes[$name]))
            throw new RouteNotExistsException($name);

        return $this->routes[$name];
    }

    /**
     * @return Route[]
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function match(Request $request): bool {
        foreach ($this->routes as $route) {
            if (preg_match($route->getPattern(), $request->getUrl(), $matches)
                    && $route->getMethod() == $request->getMethod()
                    && $route->isAjaxOnly() == $request->isAjax()) {
                $this->addParametersToRequest($matches, $route, $request);
                return true;
            }
        }

        return false;
    }

    /**
     * @param $matches
     * @param Route $route
     * @param Request $request
     */
    public function addParametersToRequest($matches, Route $route, Request $request) {
        $params = [];

        foreach ($matches as $key => $match) {
            if (is_string($key)) {
                $params[$key] = $match;
            }
        }

        $request->setParameters($params);
        $request->setRoute($route);
    }

    /**
     * @param $routes
     */
    public function setRoutes($routes) {
        $this->routes = $routes;
    }
}