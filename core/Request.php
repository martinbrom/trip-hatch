<?php

namespace Core;

class Request
{
    const NAMESPACE = 'App\Controllers\\';
    private $method;
    private $ajax;
    private $url;
    private $params;

    /** @var \Core\Route */
    private $route;

    public function __construct() {
        // TODO: WHEN IN DOUBT, DUMP IT OUT
        // var_dump($_SERVER);
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $this->url = $this->removeQueryStringVariables($_SERVER['QUERY_STRING']);
    }

    public function process() {
        $controllerClass = self::NAMESPACE . $this->route->getController() . "Controller";
        if (!class_exists($controllerClass)) {
            echo "Controller not found";
            return;
        }

        di()->register($controllerClass);
        $controller = di($controllerClass);

        $action = $this->route->getAction();
        call_user_func_array([$controller, $action], $this->params);
    }

    public function setParameters($params) {
        $this->params = $params;
    }

    public function setRoute(Route $route) {
        $this->route = $route;
    }

    public function getUrl() {
        return $this->url;
    }

    public function getMethod() {
        return $this->method;
    }

    public function isAjax(): bool {
        return $this->ajax;
    }

    protected function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            $url = (strpos($parts[0], '=') === false) ? $parts[0] : '';
        }

        return $url;
    }
}