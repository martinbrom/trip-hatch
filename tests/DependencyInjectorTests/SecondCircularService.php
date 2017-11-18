<?php

namespace Tests\DependencyInjectorTests;

class SecondCircularService
{
    private $first;

    function __construct(FirstCircularService $first) {
        $this->first = $first;
    }
}