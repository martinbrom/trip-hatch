<?php

namespace Core\Config\Exception;

use Exception;

/**
 * Class ConfigurationNotExistsException
 * @package Core\Config\Exception
 * @author Martin Brom
 */
class ConfigurationNotExistsException extends Exception
{
    /**
     * ConfigurationNotExistsException constructor.
     * @param string $flatKey
     */
    public function __construct($flatKey) {
        $message = 'Configuration ' . $flatKey . ' not found in any configuration file';
        parent::__construct($message);
    }
}
