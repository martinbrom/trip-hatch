<?php

namespace Tests\RequestTests;

use Core\DependencyInjector\DependencyInjector;
use Core\Factories\ResponseFactory;
use Core\Factories\ValidatorFactory;
use Core\Http\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function initDI() {
        $di = new DependencyInjector();
        $di->readFile('../../tests/RequestTests/service_list.php');
        return $di;
    }

    public function testGetMethod() {
        $di = $this->initDI();
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '';
        $request = new Request($di, $di->getService(ResponseFactory::class), $di->getService(ValidatorFactory::class));
        $this->assertSame($request->getMethod(), 'GET');
    }

    public function testPostMethod() {
        $di = $this->initDI();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = '';
        $request = new Request($di, $di->getService(ResponseFactory::class), $di->getService(ValidatorFactory::class));
        $this->assertSame($request->getMethod(), 'POST');
    }

    public function testDeleteMethod() {
        $di = $this->initDI();
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['method'] = 'DELETE';
        $_SERVER['QUERY_STRING'] = '';
        $request = new Request($di, $di->getService(ResponseFactory::class), $di->getService(ValidatorFactory::class));
        $this->assertSame($request->getMethod(), 'DELETE');
    }
}