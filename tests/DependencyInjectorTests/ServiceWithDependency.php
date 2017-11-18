<?php

namespace Tests\DependencyInjectorTests;

class ServiceWithDependency
{
    private $testService;

    function __construct(TestService $testService) {
        $this->testService = $testService;
    }

    public function getDependency() {
        return $this->testService;
    }
}