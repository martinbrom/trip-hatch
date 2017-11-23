<?php

namespace Tests\RoutingTests;

use Core\DependencyInjector\DependencyInjector;
use Core\Factories\ResponseFactory;
use Core\Factories\ValidatorFactory;
use Core\Http\Request;
use Core\Routing\Route;
use Core\Routing\RouteBuilder;
use Core\Routing\Router;
use PHPUnit\Framework\TestCase;

class RoutingTest extends TestCase
{
    /** @var DependencyInjector */
    private $di;

    /** @var Route */
    private $route;

    /** @var Router */
    private $router;

    /** @var Request */
    private $request;

    /** @var RouteBuilder */
    private $rb;

    public function setUp() {
        $this->di = new DependencyInjector();
        $this->di->register(RouteBuilder::class);
        $this->rb = $this->di->getService(RouteBuilder::class);
        $this->router = $this->di->getService(Router::class);
    }

    public function createRequest() {
        $this->di->register(ResponseFactory::class);
        $this->di->register(ValidatorFactory::class);
        $this->request = new Request($this->di, $this->di->getService(ResponseFactory::class), $this->di->getService(ValidatorFactory::class));
    }

    public function createRoute() {
        $this->rb->create();
        $this->route = $this->router->getRoutes()[0];
    }

    public function initInvalidRoutes() {
        $this->rb->add('POST', '/test/{name:\w+}/action/{id:\d+}', 'Test', 'action');
        $this->rb->add('POST', '/invalid url', 'Test', 'action')->ajax();
        $this->createRoute();
    }

    public function initSimpleRoute() {
        $this->rb->add('GET', '', 'Test', 'index');
        $this->createRoute();
    }

    public function initComplexRoute() {
        $this->rb = $this->di->getService(RouteBuilder::class);
        $this->rb->add('POST', '/test/{name:\w+}/action/{id:\d+}', 'Test', 'action')
            ->ajax()
            ->middleware(['test'])
            ->validate(['a' => ['required', 'min:5']]);
        $this->createRoute();
    }
    
    public function initSimpleRequest() {
        $_SERVER['QUERY_STRING'] = '';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->createRequest();
    }
    
    public function initComplexRequest() {
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        unset($_POST['method']);
        $_SERVER['QUERY_STRING'] = '/test/name/action/1';
        $this->createRequest();
    }
    
    public function testSimpleRouteRequestMatching() {
        $this->initSimpleRoute();
        $this->initSimpleRequest();
        $this->assertSame($this->router->match($this->request), true);
    }

    public function testComplexRouteRequestMatching() {
        $this->initComplexRequest();
        $this->initComplexRoute();
        $this->assertSame($this->router->match($this->request), true);
    }

    public function testInvalidRouteRequestMatching() {
        $this->initComplexRequest();
        $this->initInvalidRoutes();
        $this->assertSame($this->router->match($this->request), false);
        $this->assertSame($this->router->match($this->request), false);
    }
}
