<?php

namespace Core\Routing;

use Core\Http\Request;

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
     * @return Route[]
     */
    public function getRoutes() {
        return $this->routes;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public function preparePattern($url) {
        $url = preg_replace('/\//', '\\/', $url);
        $url = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $url);
        $url = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $url);
        $url = '/^' . $url . '$/i';
        return $url;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function match(Request $request): bool {
        foreach ($this->routes as $route) {
            if (preg_match($route->getPattern(), $request->getUrl(), $matches)
                    && $route->getMethod() == $request->getMethod()
                    && $route->isAjaxOnly() == $request->isAjaxOnly()) {
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
     * @param $method
     * @param $url
     * @param $controller
     * @param $action
     * @return Route
     */
    public function add($method, $url, $controller, $action): Route {
        $pattern = $this->preparePattern($url);
        $route = new Route($pattern, $controller, $action, $method);
        $this->routes []= $route;
        return $route;
    }
}