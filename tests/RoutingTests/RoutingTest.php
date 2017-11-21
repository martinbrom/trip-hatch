<?php

namespace Tests\RoutingTests;

use Core\DependencyInjector\DependencyInjector;
use Core\Factories\ResponseFactory;
use Core\Factories\ValidatorFactory;
use Core\Http\Request;
use Core\Routing\Route;
use Core\Routing\Router;
use PHPUnit\Framework\TestCase;

class RouteTest extends TestCase
{
    public function createRouter(): Router {
        $di = new DependencyInjector();
        $di->register(Router::class);
        return $di->getService(Router::class);
    }

    public function initBasicRoute(): Route {
        $router = $this->createRouter();
        $router->add('GET', '', 'Test', 'index');
        return $router->getRoutes()[0];
    }

    public function initComplexRoute(): Route {
        $router = $this->createRouter();
        $router->add('POST', '/test/{name:\w+}/action/{id:\d+}', 'Test', 'action')
            ->ajax()
            ->middleware(['test'])
            ->validate(['a' => ['required', 'min:5']]);
        return $router->getRoutes()[0];
    }
    
    public function initBasicRequest(): Request {
        $di = new DependencyInjector();
        $di->register(ResponseFactory::class);
        $di->register(ValidatorFactory::class);
        $_SERVER['QUERY_STRING'] = '';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        return new Request($di, $di->getService(ResponseFactory::class), $di->getService(ValidatorFactory::class));
    }
    
    public function initComplexRequest() {
        $di = new DependencyInjector();
        $di->register(ResponseFactory::class);
        $di->register(ValidatorFactory::class);
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        unset($_POST['method']);
        $_SERVER['QUERY_STRING'] = '/test/name/action/1';
        return new Request($di, $di->getService(ResponseFactory::class), $di->getService(ValidatorFactory::class));
    }

    public function testBasicRoutePattern() {
        $route = $this->initBasicRoute();
        $this->assertSame($route->getPattern(), '/^$/i');
    }

    public function testBasicRouteMethod() {
        $route = $this->initBasicRoute();
        $this->assertSame($route->getMethod(), 'GET');
    }

    public function testBasicRouteAjaxAccess() {
        $route = $this->initBasicRoute();
        $this->assertSame($route->isAjaxOnly(), false);
    }

    public function testBasicRouteMiddleware() {
        $route = $this->initBasicRoute();
        $this->assertSame($route->getMiddleware(), []);
    }

    public function testBasicRouteController() {
        $route = $this->initBasicRoute();
        $this->assertSame($route->getController(), 'Test');
    }

    public function testBasicRouteAction() {
        $route = $this->initBasicRoute();
        $this->assertSame($route->getAction(), 'index');
    }

    public function testBasicRouteValidationRules() {
        $route = $this->initBasicRoute();
        $this->assertSame($route->getValidationRules(), null);
    }

    public function testComplexRoutePattern() {
        $route = $this->initComplexRoute();
        $this->assertSame($route->getPattern(), '/^\/test\/(?P<name>\w+)\/action\/(?P<id>\d+)$/i');
    }

    public function testComplexRouteMiddleware() {
        $route = $this->initComplexRoute();
        $this->assertSame($route->getMiddleware(), ['test']);
    }

    public function testComplexRouteValidationRules() {
        $route = $this->initComplexRoute();
        $this->assertSame($route->getValidationRules(), ['a' => ['required', 'min:5']]);
    }
    
    public function testBasicRouteRequestMatching() {
        $request = $this->initBasicRequest();
        $router = $this->createRouter();
        $router->add('GET', '', 'Test', 'index');
        $this->assertSame($router->match($request), true);
    }

    public function testComplexRouteRequestMatching() {
        $request = $this->initComplexRequest();
        $router = $this->createRouter();
        $router->add('POST', '/test/{name:\w+}/action/{id:\d+}', 'Test', 'action')
            ->ajax()
            ->middleware(['test'])
            ->validate(['a' => ['required', 'min:5']]);
        $this->assertSame($router->match($request), true);
    }

    public function testInvalidRouteRequestMatching() {
        $request = $this->initComplexRequest();
        $router = $this->createRouter();
        $router->add('POST', '/test/{name:\w+}/action/{id:\d+}', 'Test', 'action');
        $this->assertSame($router->match($request), false);

        $router = $this->createRouter();
        $router->add('POST', '/invalid url', 'Test', 'action')->ajax();
        $this->assertSame($router->match($request), false);
    }
}
