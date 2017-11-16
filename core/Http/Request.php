<?php

namespace Core\Http;

use Core\DependencyInjector;
use Core\Factories\ResponseFactory;
use Core\Factories\ValidatorFactory;
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

    /** @var ResponseFactory Instance for creating responses */
    private $responseFactory;

    /** @var ValidatorFactory Instance for creating validators */
    private $validatorFactory;

    /**
     * Creates new Request instance and injects DependencyInjector, ResponseFactory and ValidatorFactory
     * @param DependencyInjector $di Instance containing registered services
     * @param ResponseFactory $responseFactory Instance for creating responses
     * @param ValidatorFactory $validatorFactory Instance for creating validators
     */
    public function __construct(DependencyInjector $di, ResponseFactory $responseFactory, ValidatorFactory $validatorFactory) {
        // TODO: WHEN IN DOUBT, DUMP IT OUT
        // var_dump($_SERVER);
        $this->di = $di;
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $this->url = $this->removeQueryStringVariables($_SERVER['QUERY_STRING']);
        $this->responseFactory = $responseFactory;
        $this->validatorFactory = $validatorFactory;
    }

    /**
     * Validates and processes request, calls action on a controller,
     * that is specified by a matching route
     */
    public function process() {
        $validator = $this->validatorFactory->make();
        $validator->setRequest($this);
        if (!$validator->validate()) {
            var_dump($validator->getErrors());
            // TODO: Redirect back
            die();
        }

        $controllerClass = self::NAMESPACE . $this->route->getController() . "Controller";
        if (!class_exists($controllerClass)) {
            // TODO: Error message controller not found
            return $this->responseFactory->redirect('/');
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

    /**
     * @param $key
     * @return string
     */
    public function getInput($key) {
        return $this->method == 'GET' ? (isset($_GET[$key]) ? $_GET[$key] : null) : (isset($_POST[$key]) ? $_POST[$key] : null);
    }
}