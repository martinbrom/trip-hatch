<?php

namespace App\Core;

class DependencyInjector
{
    protected $services = [];

    private function register($className) {
        $this->services[$className] = $this->instantiate($className);
    }

    private function serviceExists($name) {
        return array_key_exists($name, $this->services);
    }

    public function getService($name) {
        if (!array_key_exists($name, $this->services))
            throw new \Exception("Service $name doesn't exist!");

        return $this->services[$name];
    }

    public function getServices() {
        return $this->services;
    }

    private function instantiate($className) {
        if ($this->serviceExists($className))
            return $this->services[$className];

        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();
        if (is_null($constructor))
            return new $className;

        $parameters = $constructor->getParameters();
        $dependencies = array();
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();

            if (is_null($dependency)) {
                if (!$parameter->isDefaultValueAvailable())
                    throw new \Exception("No default value or type supplied for class $className!");

                $service = $parameter->getDefaultValue();
            } else {
                $service = $this->instantiate($dependency->name);
            }

            if (!$this->serviceExists($dependency->name))
                $this->services[$dependency->name] = $service;

            $dependencies []= $service;
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    public function readFile($fileName) {
        $content = require_once $fileName;
        foreach ($content as $serviceName) {
            $this->register($serviceName);
        }
    }
}