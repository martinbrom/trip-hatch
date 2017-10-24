<?php

namespace App\Controllers;

class TestController
{
    private $testRepository;

    public function __construct(\App\Repositories\TestRepository $testRepository) {
        $this->testRepository = $testRepository;
    }

    public function index() {
        $testInstances = $this->testRepository->getAll();
        echo '<pre>';
        var_dump($testInstances);
        echo '</pre>';
    }
}