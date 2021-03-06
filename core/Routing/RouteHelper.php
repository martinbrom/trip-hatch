<?php

namespace Core\Routing;

/**
 * Class RouteHelper
 * @package Core\Routing
 * @author Martin Brom
 */
class RouteHelper
{
    /** @var Router */
    private $router;

    /**
     * RouteHelper constructor.
     * @param Router $router
     */
    function __construct(Router $router) {
        $this->router = $router;
    }

    /**
     * @param $name
     * @param array $params
     * @return mixed
     */
    public function get($name, $params = []) {
        $route = $this->router->getRoute($name);
        return $this->replace($route->getUrl(), $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed
     */
    public function replace($url, $params = []) {
        foreach ($params as $key => $value) {
            $url = str_replace('/' . $key, '/' . $value, $url); // slash prevents sub-pattern replacing
        }

        return $url;
    }
}