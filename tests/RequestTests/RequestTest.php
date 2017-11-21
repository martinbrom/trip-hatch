<?php

namespace Tests\RequestTests;

use App\Exception\ControllerNotFoundException;
use App\Exception\MethodNotFoundException;
use Core\DependencyInjector\DependencyInjector;
use Core\Factories\ResponseFactory;
use Core\Factories\ValidatorFactory;
use Core\Http\Request;
use Core\Routing\Router;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function initDI() {
        $di = new DependencyInjector();
        $di->readFile('../../tests/RequestTests/service_list.php');
        return $di;
    }

    public function createRequest(): Request {
        $di = $this->initDI();
        return new Request($di, $di->getService(ResponseFactory::class), $di->getService(ValidatorFactory::class));
    }

    public function createRouter(): Router {
        $di = $this->initDI();
        $di->register(Router::class);
        return $di->getService(Router::class);
    }

    public function testGetMethod() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';
        $request = $this->createRequest();
        $this->assertSame($request->getMethod(), 'GET');
    }

    public function testPostMethod() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = '';
        $request = $this->createRequest();
        $this->assertSame($request->getMethod(), 'POST');
    }

    public function testDeleteMethod() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['method'] = 'DELETE';
        $_SERVER['QUERY_STRING'] = '';
        $request = $this->createRequest();
        $this->assertSame($request->getMethod(), 'DELETE');
    }

    public function testUrl() {
        $_SERVER['QUERY_STRING'] = 'abcd';
        $request = $this->createRequest();
        $this->assertSame($request->getUrl(), 'abcd');
    }

    public function testAjaxState() {
        unset($_SERVER['HTTP_X_REQUESTED_WITH']);
        $request = $this->createRequest();
        $this->assertSame($request->isAjax(), false);

        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';
        $request = $this->createRequest();
        $this->assertSame($request->isAjax(), true);
        
        $_SERVER['HTTP_X_REQUESTED_WITH'] = 'abcd';
        $request = $this->createRequest();
        $this->assertSame($request->isAjax(), false);
    }

    public function testGetInput() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET['input'] = 'value';
        $request = $this->createRequest();
        $this->assertSame($request->getInput('input'), 'value');
    }

    public function testPostInput() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['input'] = 'value';
        $request = $this->createRequest();
        $this->assertSame($request->getInput('input'), 'value');
    }

    public function testNonExistentInput() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        unset($_GET['non-existent-input']);
        $request = $this->createRequest();
        $this->assertSame($request->getInput('non-existent-input'), null);
    }

    public function testMixedInput() {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['input'] = 'value';
        $_POST['input'] = 'value2';
        $request = $this->createRequest();
        $this->assertSame($request->getInput('input'), 'value2');
    }

    public function testGettingParameter() {
        $router = new Router();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '1';
        $router->add('GET', '{id:\d+}', 'Test', 'testAction');
        $request = $this->createRequest();
        $router->match($request);
        $this->assertSame($request->getParameter('non-existent-parameter'), null);
        $this->assertSame($request->getParameter('id'), '1');
    }

    public function testNonExistentController() {
        $this->expectException(ControllerNotFoundException::class);
        $router = new Router();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router->add('GET', '', 'Test', 'testAction');
        $request = $this->createRequest();
        $request->setRoute($router->getRoutes()[0]);
        $request->process();
    }

    public function testNonExistentAction() {
        $this->expectException(MethodNotFoundException::class);
        $router = new Router();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $router->add('GET', '', 'Trip', 'non-existent-action-name');
        $request = $this->createRequest();
        $request->setRoute($router->getRoutes()[0]);
        $request->process();
    }

    // TODO: Request validation test
}