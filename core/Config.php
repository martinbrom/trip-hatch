<?php

namespace Core;

class Config
{
    private $configurations;

    public function __construct() {
        $configFiles = scandir("../config");
        foreach ($configFiles as $file) {
            $this->loadConfigFile($file);
        }
    }

    private function loadConfigFile($fileName) {
        $path = "../config/" . $fileName;

        if (is_file($path)) {
            $configurationData = require_once $path;

            foreach ($configurationData as $key => $value) {
                $this->configurations[$key] = $value;
            }
        }
    }

    // array_key_exists on its own provides a complete but slow array check
    // using isset returns false for elements, that have a value of NULL,
    // using both with OR operations ensures optimal runtime by only using
    // array_key_exists function when isset returns false
    public function get($key) {
        return (isset($this->configurations[$key]) || array_key_exists($key, $this->configurations))
            ? $this->configurations[$key] : NULL;
    }
}