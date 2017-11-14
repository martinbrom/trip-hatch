<?php

namespace Core\Http;

use Core\DependencyInjector;
use Core\Http\Response\RedirectResponse;
Use Core\Routing\Route;
use Core\Validator;

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

    /** @var bool If request is sent using ajax */
    private $ajax;

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
    public function __construct(DependencyInjector $di) {
        // TODO: WHEN IN DOUBT, DUMP IT OUT
        // var_dump($_SERVER);
        $this->di = $di;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $this->url = $this->removeQueryStringVariables($_SERVER['QUERY_STRING']);
    }

    /**
     * Validates and processes request, calls action on a controller,
     * that is specified by a matching route
     */
    public function process() {
        $validator = new Validator();
        $validator->setRequest($this);
        if (!$validator->validate()) {
            // TODO: Error handling
        }

        $controllerClass = self::NAMESPACE . $this->route->getController() . "Controller";
        if (!class_exists($controllerClass)) {
            // TODO: Error message controller not found
            return new RedirectResponse('/');
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
    public function isAjax(): bool {
        return $this->ajax;
    }

    /**
     * Returns a list of required middleware to be run before and after the request
     * @return array Required middleware for the request
     */
    public function getMiddleware() {
        return $this->route->getMiddleware();
    }

    /**
     * Returns validation rules for a route, that matches this request
     * @return array Validation rules
     */
    public function getValidationRules() {
        return $this->route->getValidationRules();
    }




    public function getData($key) {
        if (isset($this->params[$key])) return $this->params[$key];
        return $this->getInput($key);
    }

    public function getInput($key) {
        $input = $this->method == 'GET' ? $_GET[$key] : $_POST[$key];
        return $input;
    }
}