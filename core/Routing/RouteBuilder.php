<?php

namespace Core\Routing;

use Core\Routing\Exception\RouteAjaxStateAlreadySetException;
use Core\Routing\Exception\RouteMiddlewareAlreadySetException;
use Core\Routing\Exception\RouteNameAlreadyExistsException;
use Core\Routing\Exception\RouteNameAlreadySetException;
use Core\Routing\Exception\RouteValidationRulesAlreadySetException;

/**
 * Class RouteBuilder
 * @package Core\Routing
 * @author Martin Brom
 */
class RouteBuilder
{
    /** @var Router */
    private $router;

    /** @var int */
    private $firstFreeRouteID;

    /** @var Route */
    private $currentRoute;

    /** @var Route[] */
    private $routes;

    /** @var bool */
    private $middlewareSet;

    /** @var bool */
    private $ajaxStateSet;

    /** @var bool */
    private $validationRulesSet;

    /** @var bool */
    private $nameSet;

    /**
     * RouteBuilder constructor.
     * @param Router $router
     */
    function __construct(Router $router) {
        $this->router = $router;
        $this->firstFreeRouteID = 0;
    }

    /**
     *
     */
    public function create() {
        $this->router->setRoutes($this->routes);
    }

    /**
     * @param $method
     * @param $url
     * @param $controller
     * @param $action
     * @return RouteBuilder
     */
    public function add($method, $url, $controller, $action): self {
        $this->cleanControlVariables();
        $this->currentRoute = new Route($url, $controller, $action, $method);
        $this->routes[$this->firstFreeRouteID] = $this->currentRoute;
        $this->firstFreeRouteID++;
        return $this;
    }

    /**
     * @param $middleware
     * @return RouteBuilder
     * @throws RouteMiddlewareAlreadySetException
     */
    public function middleware($middleware): self {
        if ($this->middlewareSet)
            throw new RouteMiddlewareAlreadySetException();

        $this->middlewareSet = true;
        $this->currentRoute->setMiddleware($middleware);
        return $this;
    }

    /**
     * @return RouteBuilder
     * @throws RouteAjaxStateAlreadySetException
     */
    public function ajax(): self {
        if ($this->ajaxStateSet)
            throw new RouteAjaxStateAlreadySetException();

        $this->ajaxStateSet = true;
        $this->currentRoute->setAjaxOnly(true);
        return $this;
    }

    /**
     * @param $rules
     * @return RouteBuilder
     * @throws RouteValidationRulesAlreadySetException
     */
    public function validate($rules): self {
        if ($this->validationRulesSet)
            throw new RouteValidationRulesAlreadySetException();

        $this->validationRulesSet = true;
        $this->currentRoute->setValidationRules($rules);
        return $this;
    }

    /**
     * @param $name
     * @return RouteBuilder
     * @throws RouteNameAlreadyExistsException
     * @throws RouteNameAlreadySetException
     */
    public function name($name): self {
        if ($this->nameSet)
            throw new RouteNameAlreadySetException();

        if (isset($this->routes[$name]))
            throw new RouteNameAlreadyExistsException($name);

        $this->nameSet = true;
        $this->firstFreeRouteID--;
        unset($this->routes[$this->firstFreeRouteID]);
        $this->routes[$name] = $this->currentRoute;
        return $this;
    }

    /**
     *
     */
    public function cleanControlVariables() {
        $this->middlewareSet = false;
        $this->ajaxStateSet = false;
        $this->validationRulesSet = false;
        $this->nameSet = false;
    }
}