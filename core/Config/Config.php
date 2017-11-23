<?php

namespace Core\Config;

use Core\Config\Exception\ConfigurationNotExistsException;
use Core\FlatArray;

/**
 * Holds and provides all configuration values
 * @package Core
 * @author Martin Brom
 */
class Config
{
    /** Location of configuration files */
    const FOLDER = "../../config";

    private $configurations;

    public function __construct() {
        $this->configurations = new FlatArray($this->loadConfigurations());
    }

    public function get($flatKey) {
        $configuration = $this->configurations->get($flatKey);
        if ($configuration === null)
            throw new ConfigurationNotExistsException($flatKey);

        return $configuration;
    }

    public function getAll() {
        return $this->configurations->getAll();
    }

    private function loadConfigurations(): array {
        $result = [];
        $files = glob(__DIR__ . '/' . self::FOLDER . '/*');
        foreach ($files as $file) {
            $key = basename($file, '.php');
            $result[$key] = require($file);
        }
        return $result;
    }
}