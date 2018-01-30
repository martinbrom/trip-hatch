<?php

namespace Core\Http;

use App\Exception\ControllerNotFoundException;
use App\Exception\MethodNotFoundException;
use Core\DependencyInjector\DependencyInjector;
use Core\Factories\ResponseFactory;
use Core\Factories\ValidatorFactory;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\Response;
Use Core\Routing\Route;
use Core\Session;

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

    /** @var array|false All http request headers */
    private $headers;

    /** @var DependencyInjector Instance containing registered services */
    private $di;

    /** @var ResponseFactory Instance for creating responses */
    private $responseFactory;

    /** @var ValidatorFactory Instance for creating validators */
    private $validatorFactory;

    /** @var Session */
    private $session;

    /**
     * Creates new Request instance and injects DependencyInjector, ResponseFactory and ValidatorFactory
     * @param DependencyInjector $di Instance containing registered services
     * @param ResponseFactory $responseFactory Instance for creating responses
     * @param ValidatorFactory $validatorFactory Instance for creating validators
     * @param Session $session
     */
    public function __construct(
            DependencyInjector $di,
            ResponseFactory $responseFactory,
            ValidatorFactory $validatorFactory,
            Session $session) {
        // var_dump($_SERVER);
        $this->di = $di;
        $this->method = $this->determineMethod();
        $this->ajax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
        $this->url = $this->removeQueryStringVariables($_SERVER['QUERY_STRING']);
        $this->headers = getallheaders();
        $this->responseFactory = $responseFactory;
        $this->validatorFactory = $validatorFactory;
        $this->session = $session;
    }

    /**
     * Validates and processes request, calls action on a controller,
     * that is specified by a matching route
     * @return Response
     * @throws ControllerNotFoundException
     * @throws MethodNotFoundException
     */
    public function process() {
        $validator = $this->validatorFactory->make();
        $validator->setRequest($this);

        if (!$validator->validate()) {
            if ($this->isAjax()) {
                return $this->responseFactory->json(['validation_errors' => $validator->getErrors()], 200);
            }

            $this->session->set('validation_errors', $validator->getErrors());
            return $this->responseFactory->redirectBack();
        }

        $controllerClass = self::NAMESPACE . $this->route->getController() . "Controller";
        if (!class_exists($controllerClass))
            throw new ControllerNotFoundException($controllerClass);

        $this->di->register($controllerClass);
        $controller = $this->di->getService($controllerClass);
        $controller->setResponseFactory($this->responseFactory);

        $action = $this->route->getAction();
        if (!method_exists($controller, $action))
            throw new MethodNotFoundException($controllerClass, $action);

        return call_user_func([$controller, $action], $this);
    }

    public function determineMethod() {
        if ($_SERVER['REQUEST_METHOD'] == 'GET') return 'GET';
        return isset($_POST['method']) && $_POST['method'] == 'DELETE' ? 'DELETE' : 'POST';
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
     * Returns all http request headers
     * @return array|false All http request headers
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Returns a http request header with given name
     * @param string $name Name of http header
     * @return mixed|null Header if name exists, null otherwise
     */
    public function getHeader(string $name) {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    /**
     * Returns input with given key from super global
     * variable matching request method
     * @param string $key Name of input
     * @return string|null Input if key exists, null otherwise
     */
    public function getInput($key) {
        if ($this->method == 'GET') {
            if (isset($_GET[$key]))
                return $_GET[$key];

            return null;
        }

        if (isset($_POST[$key]))
            return $_POST[$key];

        if (isset($_FILES[$key]))
            return $_FILES[$key];

        return null;
    }

    /**
     * Returns a parameter parsed from route
     * @param string $param Name of parameter
     * @return mixed|null Parameter if present, null otherwise
     */
    public function getParameter($param) {
        return isset($this->params[$param]) ? $this->params[$param] : null;
    }
}