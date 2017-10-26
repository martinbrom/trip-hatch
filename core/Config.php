<?php

namespace Core;

/**
 * Holds and provides all configuration values
 * @package Core
 * @author Martin Brom
 */
class Config
{
    /** @var array Array of all configurations */
    private $configurations;

    /**
     * Creates new instance and loads all configuration files
     */
    public function __construct() {
        $configFiles = scandir("../config");
        foreach ($configFiles as $file) {
            $this->loadConfigFile($file);
        }
    }

    /**
     * Loads given configuration file and populates
     * configurations array with its contents
     * @param string $fileName Name of configuration file
     */
    private function loadConfigFile($fileName) {
        $path = "../config/" . $fileName;

        if (is_file($path)) {
            $configurationData = require_once $path;

            foreach ($configurationData as $key => $value) {
                $this->configurations[$key] = $value;
            }
        }
    }

    /**
     * Checks if given configurations is registered
     * @param string $key Configuration name
     * @return mixed|null True if key exists in configurations, false otherwise
     */
    public function get($key) {
        // array_key_exists on its own provides a complete but slow array check
        // using isset returns false for elements, that have a value of NULL,
        // using both with OR operations ensures optimal runtime by only using
        // array_key_exists function when isset returns false

        return (isset($this->configurations[$key]) || array_key_exists($key, $this->configurations))
            ? $this->configurations[$key] : NULL;
    }
}