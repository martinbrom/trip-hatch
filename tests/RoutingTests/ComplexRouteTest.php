<?php

namespace RoutingTests;

use Core\DependencyInjector\DependencyInjector;
use Core\Routing\Route;
use Core\Routing\RouteBuilder;
use Core\Routing\Router;
use PHPUnit\Framework\TestCase;

class ComplexRouteTest extends TestCase
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
        $rb->add('POST', '/test/{name:\w+}/action/{id:\d+}', 'Test', 'action')
            ->ajax()
            ->middleware(['test'])
            ->validate(['a' => ['required', 'min:5']]);
        $rb->create();
        $this->router = $this->di->getService(Router::class);
        $this->route = $this->router->getRoutes()[0];
    }

    public function testComplexRoutePattern() {
        $this->assertSame($this->route->getPattern(), '/^\/test\/(?P<name>\w+)\/action\/(?P<id>\d+)$/i');
    }

    public function testComplexRouteMiddleware() {
        $this->assertSame($this->route->getMiddleware(), ['test']);
    }

    public function testComplexRouteValidationRules() {
        $this->assertSame($this->route->getValidationRules(), ['a' => ['required', 'min:5']]);
    }
}