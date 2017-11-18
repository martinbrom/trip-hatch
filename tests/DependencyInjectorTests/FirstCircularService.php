<?php

namespace Tests\DependencyInjectorTests;

class FirstCircularService
{
    private $second;

    function __construct(SecondCircularService $second) {
        $this->second = $second;
    }
}