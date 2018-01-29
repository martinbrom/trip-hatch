<?php

namespace Core\Mail\Exception;

/**
 * Class InvalidEmailMessageException
 * @package Core\Mail\Exception
 * @author Martin Brom
 */
class InvalidEmailMessageException extends EmailException
{
    /**
     * InvalidEmailMessageException constructor.
     * @param string $emailMessage
     */
    function __construct($emailMessage) {
        $message = "Email message: " . $emailMessage . " is invalid";
        parent::__construct($message);
    }
}
