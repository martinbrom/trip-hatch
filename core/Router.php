<?php

namespace Core;

class Router
{
    const NAMESPACE = 'App\Controllers\\';
    protected $routes = [];
    protected $params = [];
    private $di;

    public function __construct(\Core\DependencyInjector $di) {
        $this->di = $di;
    }

    public function getRoutes() {
        return $this->routes;
    }

    public function getParams() {
        return $this->params;
    }

    public function add($route, $controller, $action) {
        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z-]+)', $route);
        $route = preg_replace('/\{([a-z]+):([^\}]+)\}/', '(?P<\1>\2)', $route);
        $route = '/^' . $route . '$/i';

        $this->routes[$route] = [
            'controller'    => $controller,
            'action'        => $action
        ];
    }

    public function match($url) {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }

                $this->params = $params;
                return true;
            }
        }

        return false;
    }

    public function dispatch($url) {
        $url = $this->removeQueryStringVariables($url);
        if (!$this->match($url)) {
            // TODO: 404
            echo "404";
            return;
        }

        $controller = self::NAMESPACE . $this->params['controller'] . "Controller";
        if (!class_exists($controller)) {
            echo "Controller not found";
            return;
        }

        $action = $this->params['action'];

        // TODO: Method doesn't exist
        if (!method_exists($controller, $action)) {
            echo "Method " . $action . " on controller " . $controller . " doesn't exist";
            return;
        }

        // TODO: Come up with a better way to store route parameters in router so you don't have to unset them every time
        unset($this->params['action'], $this->params['controller']);

        $this->di->register($controller);
        $controller = $this->di->getService($controller);
        $controller->$action(...$this->params);
    }

    protected function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            $url = (strpos($parts[0], '=') === false) ? $parts[0] : '';
        }

        return $url;
    }
}