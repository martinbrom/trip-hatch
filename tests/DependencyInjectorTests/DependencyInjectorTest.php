<?php

namespace Tests\DependencyInjectorTests;

use Core\DependencyInjector\DependencyInjector;
use Core\DependencyInjector\Exception\CircularDependencyFoundException;
use Core\DependencyInjector\Exception\DependencyTypeNotFoundException;
use Core\DependencyInjector\Exception\ServiceNotExistsException;
use PHPUnit\Framework\TestCase;

class DependencyInjectorTest extends TestCase
{
    /** @var DependencyInjector */
    public $di;

    public function setUp() {
        $this->di = new DependencyInjector();
        $this->di->readFile('../../tests/DependencyInjectorTests/service_list.php');
    }

    public function testSimpleService() {
        $service = $this->di->getService(TestService::class);
        $this->assertInstanceOf(TestService::class, $service);
    }
    
    public function testServiceWithDependency() {
        $service = $this->di->getService(ServiceWithDependency::class);
        $this->assertInstanceOf(ServiceWithDependency::class, $service);
        $this->assertInstanceOf(TestService::class, $service->getDependency());
    }

    public function testDependencyInheritance() {
        $service = $this->di->getService(ExtendedService::class);
        $this->assertInstanceOf(ExtendedService::class, $service);
        $this->assertInstanceOf(TestService::class, $service->getDependency());
    }

    public function testNonExistentService() {
        $this->expectException(ServiceNotExistsException::class);
        $this->di->getService('NonExistentService');
    }

    public function testServiceDependencyTypeNotFound() {
        $this->expectException(DependencyTypeNotFoundException::class);
        $this->di->register(ServiceWithoutDefaultValue::class);
    }

    public function testCircularDependency() {
        $this->expectException(CircularDependencyFoundException::class);
        $this->di->register(FirstCircularService::class);
    }
}
