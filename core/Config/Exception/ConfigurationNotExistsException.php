<?php

namespace Core\Config\Exception;

use Exception;

class ConfigurationNotExistsException extends Exception
{
    public function __construct($flatKey) {
        $message = 'Configuration ' . $flatKey . ' not found in any configuration file';
        parent::__construct($message);
    }
}
