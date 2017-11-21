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
    // TODO: Named routes ?

    /**
     * Creates new instance with four needed route parameters
     * @param string $pattern Regex URL pattern
     * @param string $controller Name of controller to be called
     * @param string $action Name of action on the controller
     * @param string $method HTTP request method
     */
    public function __construct($pattern, $controller, $action, $method = "GET") {
        $this->pattern = $pattern;
        $this->controller = $controller;
        $this->action = $action;
        $this->method = $method;
    }

    /**
     * Sets aliases of middleware
     * @param array $middleware List of middleware aliases
     * @return self Returns itself for chaining setters
     */
    public function middleware($middleware): self {
        $this->middleware = $middleware;
        return $this;
    }

    /**
     * Sets ajax-only state of Route to true
     * @return self Returns itself for chaining setters
     */
    public function ajax(): self {
        $this->ajaxOnly = true;
        return $this;
    }

    /**
     * Sets validation rules for route-matching request
     * @param array $rules Validation rules
     * @return self Returns itself for chaining setters
     */
    public function validate($rules) {
        $this->validationRules = $rules;
        return $this;
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
     * Returns whether the route is only accessible via AJAX
     * @return bool True if route can only be accessed via AJAX, false otherwise
     */
    public function isAjaxOnly(): bool {
        return $this->ajaxOnly;
    }

    /**
     * Returns validation rules
     * @return array Validation rules
     */
    public function getValidationRules() {
        return $this->validationRules;
    }
}