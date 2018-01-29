<?php

namespace Core\Mail\Exception;

use Exception;

/**
 * Class EmailException
 * @package Core\Mail\Exception
 * @author Martin Brom
 */
class EmailException extends Exception
{
    /**
     * EmailException constructor.
     * @param string $message
     */
    function __construct($message) {
        parent::__construct($message);
    }
}
