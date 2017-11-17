<?php

namespace Core;

/**
 * Handles registering all services and their dependencies
 * @package Core
 * @author Martin Brom
 */
class DependencyInjector
{
    /** @var array Array of all loaded services */
    protected $services = [];

    /**
     * DependencyInjector constructor.
     */
    function __construct() {
        $this->services[self::class] = $this;
    }

    /**
     * Registers a new service
     * @param string $className Name of service class
     */
    public function register($className) {
        $this->services[$className] = $this->instantiate($className);
    }

    /**
     * Checks if service instance of given name exists
     * @param string $name Name of service class
     * @return bool True if service is already loaded, false otherwise
     */
    private function serviceExists($name) {
        return array_key_exists($name, $this->services);
    }

    /**
     * Returns a service instance of given name
     * @param string $name Name of service class
     * @return mixed Service class instance
     * @throws \Exception If given service isn't loaded
     */
    public function getService($name) {
        if (!array_key_exists($name, $this->services))
            throw new \Exception("Service $name doesn't exist!");

        return $this->services[$name];
    }

    /**
     * Returns all loaded services
     * @return array Array of all loaded services
     */
    public function getServices() {
        return $this->services;
    }

    /**
     * Creates a service class with all its dependencies
     * Returns given service class if it is already loaded
     * @param string $className Name of service class
     * @return mixed|object Instance of service class
     * @throws \Exception If no default value or type is supplied
     *                    in service class constructor
     */
    private function instantiate($className) {

        // service was already created, return the instance
        if ($this->serviceExists($className))
            return $this->services[$className];

        $reflection = new \ReflectionClass($className);
        $constructor = $reflection->getConstructor();

        // if the class has no constructor, that means it has
        // no dependencies, create it
        if (is_null($constructor))
            return new $className;

        // get all dependencies from constructor parameters
        // and instantiate all of them
        $parameters = $constructor->getParameters();
        $dependencies = array();
        foreach ($parameters as $parameter) {
            $dependency = $parameter->getClass();

            // if a parameter has no set class, it needs to have a default value
            if (is_null($dependency)) {
                if (!$parameter->isDefaultValueAvailable())
                    throw new \Exception("No default value or type supplied for class $className!");

                $service = $parameter->getDefaultValue();
            } else {
                $service = $this->instantiate($dependency->name);
            }

            // if this is the first time service is being
            // called for save it for further use
            if (!$this->serviceExists($dependency->name))
                $this->services[$dependency->name] = $service;

            $dependencies []= $service;
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    /**
     * Reads a services config file and loads all given services
     * @param string $fileName Name of services config file
     */
    public function readFile($fileName) {
        $content = require_once $fileName;
        foreach ($content as $serviceName) {
            $this->register($serviceName);
        }
    }
}