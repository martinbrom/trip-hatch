<?php

namespace Core\Http;

use Core\DependencyInjector;
Use Core\Routing\Route;

/**
 * Contains SERVER request data and calls action on a controller
 * specified by a route, that matches request URL
 * @package Core
 * @author Martin Brom
 */
class Request
{
    /** Location of controller classes */
    const NAMESPACE = 'App\Controllers\\';

    /** @var string HTTP request method */
    private $method;

    /** @var bool Only ajax access allowed */
    private $ajaxOnly;

    /** @var string Request URL */
    private $url;

    /** @var array Parameters parsed from URL */
    private $params;

    /** @var Route Route that matches request URL */
    private $route;

    /** @var DependencyInjector Instance containing registered services */
    private $di;

    /**
     * Creates new Request instance and injects DependencyInjector instance
     * @param DependencyInjector $di Instance containing registered services
     */
    public function __construct(\Core\DependencyInjector $di) {
        // TODO: WHEN IN DOUBT, DUMP IT OUT
        // var_dump($_SERVER);
        $this->di = $di;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->ajaxOnly = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $this->url = $this->removeQueryStringVariables($_SERVER['QUERY_STRING']);
    }

    /**
     * Processes request, calls action on a controller,
     * that is specified by a matching route
     */
    public function process() {
        $controllerClass = self::NAMESPACE . $this->route->getController() . "Controller";
        if (!class_exists($controllerClass)) {
            echo "Controller not found";
            // TODO: Response redirect to index, controller not found
            return;
        }

        $this->di->register($controllerClass);
        $controller = $this->di->getService($controllerClass);

        $action = $this->route->getAction();
        return call_user_func_array([$controller, $action], $this->params);
    }

    /**
     * Cleans "ugly" URL
     * @param string $url Full ugly URL
     * @return string Clean URL
     */
    protected function removeQueryStringVariables($url) {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            $url = (strpos($parts[0], '=') === false) ? $parts[0] : '';
        }

        return $url;
    }

    /**
     * Sets parameters from URL
     * @param array $params Parameters from URL
     */
    public function setParameters($params) {
        $this->params = $params;
    }

    /**
     * Sets a matching Route instance
     * @param Route $route
     */
    public function setRoute(Route $route) {
        $this->route = $route;
    }

    /**
     * Returns request url
     * @return string Request url
     */
    public function getUrl() {
        return $this->url;
    }

    /**
     * Returns request HTTP method
     * @return string HTTP method
     */
    public function getMethod() {
        return $this->method;
    }

    /**
     * Returns whether the request was sent using AJAX
     * @return bool True if the request was sent using AJAX, false otherwise
     */
    public function isAjaxOnly(): bool {
        return $this->ajaxOnly;
    }

    /**
     * Returns a list of required middleware to be run before and after the request
     * @return array List of required middleware for the request
     */
    public function getMiddleware() {
        return $this->route->getMiddleware();
    }
}