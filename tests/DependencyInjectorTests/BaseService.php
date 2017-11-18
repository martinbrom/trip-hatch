<?php

namespace Tests\DependencyInjectorTests;

class BaseService
{
    private $testService;

    function __construct(TestService $testService) {
        $this->testService = $testService;
    }

    public function getDependency() {
        return $this->testService;
    }
}