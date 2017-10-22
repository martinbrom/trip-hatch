<?php

namespace App\Core;

class Config
{
    private static $configurations;

    public static function initialize() {
        $configFiles = scandir("../config");
        foreach ($configFiles as $file) {
            self::loadConfigFile($file);
        }
    }

    private static function loadConfigFile($fileName) {
        $path = "../config/" . $fileName;

        if (is_file($path)) {
            $configurationData = require_once $path;

            foreach ($configurationData as $key => $value) {
                self::$configurations[$key] = $value;
            }
        }
    }

    // array_key_exists on its own provides a complete but slow array check
    // using isset returns false for elements, that have a value of NULL,
    // using both with OR operations ensures optimal runtime by only using
    // array_key_exists function when isset returns false
    public static function get($key) {
        return (isset(self::$configurations[$key]) || array_key_exists($key, self::$configurations))
            ? self::$configurations[$key] : NULL;
    }
}