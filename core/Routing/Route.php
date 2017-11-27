<?php

namespace Core\Routing;

/**
 * Contains route related data, such as regex URL pattern,
 * controller with action, allowed HTTP request method
 * and aliases of middleware to be run
 * @package Core\Routing
 * @author Martin Brom
 */
class Route
{
    /** @var string Regex URL pattern */
    private $pattern;

    /** @var string URL with parameter placeholders */
    private $url;

    /** @var string Name of controller to be called when accessing this route */
    private $controller;

    /** @var string Name of action on a controller to be called when accessing this route */
    private $action;

    /** @var string Allowed HTTP request method */
    private $method;

    /** @var array Aliases of middleware to be run before and after processing request */
    private $middleware = [];

    /** @var bool True if route can only be accessed via AJAX, false otherwise */
    private $ajaxOnly = false;

    /** @var array Validation rules for a request, that matches this route */
    private $validationRules;

    /**
     * Creates new instance with four needed route parameters
     * @param string $url Url of route with regex parameter placeholders
     * @param string $controller Name of controller to be called
     * @param string $action Name of action on the controller
     * @param string $method HTTP request method
     */
    public function __construct($url, $controller, $action, $method = "GET") {
        $this->url = $this->prepareCleanUrl($url);
        $this->pattern = $this->preparePattern($url);
        $this->controller = $controller;
        $this->action = $action;
        $this->method = $method;
    }

    /**
     * @param $url
     * @return mixed
     */
    public function prepareCleanUrl($url) {
        $url = str_replace(':\d+', '', $url);
        $url = str_replace(':\w+', '', $url);
        $url = str_replace('{', '', $url);
        $url = str_replace('}', '', $url);
        $url = '/' . $url;
        return $url;
    }

    /**
     * @param $url
     * @return mixed|string
     */
    public function preparePattern($url) {
        $url = preg_replace('/\//', '\\/', $url);
        $url = preg_replace('/\{([a-z_-]+)\}/', '(?P<\1>[a-z-]+)', $url);
        $url = preg_replace('/\{([a-z_-]+):([^\}]+)\}/', '(?P<\1>\2)', $url);
        $url = '/^' . $url . '$/i';
        return $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string {
        return $this->url;
    }

    /**
     * Returns a regex URL pattern
     * @return string Regex URL pattern
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * Returns a name of controller
     * @return string Name of controller
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * Returns a name of action on the controller
     * @return string Name of action
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * Returns HTTP request method
     * @return string HTTP request method
     */
    public function getMethod(): string {
        return $this->method;
    }

    /**
     * Returns aliases of route middleware
     * @return array Aliases of route middleware
     */
    public function getMiddleware(): array {
        return $this->middleware;
    }

    /**
     * @param $middleware
     */
    public function setMiddleware($middleware) {
        $this->middleware = $middleware;
    }

    /**
     * Returns whether the route is only accessible via AJAX
     * @return bool True if route can only be accessed via AJAX, false otherwise
     */
    public function isAjaxOnly(): bool {
        return $this->ajaxOnly;
    }

    /**
     * @param $ajaxOnly
     */
    public function setAjaxOnly($ajaxOnly) {
        $this->ajaxOnly = $ajaxOnly;
    }

    /**
     * Returns validation rules
     * @return array Validation rules
     */
    public function getValidationRules() {
        return $this->validationRules;
    }

    /**
     * @param $rules
     */
    public function setValidationRules($rules) {
        $this->validationRules = $rules;
    }
}