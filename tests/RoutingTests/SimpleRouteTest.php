<?php

use Core\DependencyInjector\DependencyInjector;
use Core\Routing\Route;
use Core\Routing\RouteBuilder;
use Core\Routing\Router;
use PHPUnit\Framework\TestCase;

class SimpleRouteTest extends TestCase
{
    /** @var DependencyInjector */
    private $di;

    /** @var Route */
    private $route;

    /** @var Router */
    private $router;

    public function setUp() {
        $this->di = new DependencyInjector();
        $this->di->register(RouteBuilder::class);
        $rb = $this->di->getService(RouteBuilder::class);
        $rb->add('GET', '', 'Test', 'index');
        $rb->create();
        $this->router = $this->di->getService(Router::class);
        $this->route = $this->router->getRoutes()[0];
    }

    public function testRoutePattern() {
        $this->assertSame($this->route->getPattern(), '/^$/i');
    }

    public function testRouteMethod() {
        $this->assertSame($this->route->getMethod(), 'GET');
    }

    public function testRouteAjaxAccess() {
        $this->assertSame($this->route->isAjaxOnly(), false);
    }

    public function testRouteMiddleware() {
        $this->assertSame($this->route->getMiddleware(), []);
    }

    public function testRouteController() {
        $this->assertSame($this->route->getController(), 'Test');
    }

    public function testRouteAction() {
        $this->assertSame($this->route->getAction(), 'index');
    }

    public function testRouteValidationRules() {
        $this->assertSame($this->route->getValidationRules(), null);
    }
}
