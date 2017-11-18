<?php

namespace Tests\DependencyInjectorTests;

class ServiceWithoutDefaultValue
{
    private $testService;

    function __construct($testService) {
        $this->testService = $testService;
    }
}