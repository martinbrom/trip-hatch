<?php

namespace Core\Mail\Exception;

/**
 * Class InvalidEmailRecipientException
 * @package Core\Mail\Exception
 * @author Martin Brom
 */
class InvalidEmailRecipientException extends EmailException
{
    /**
     * InvalidEmailRecipientException constructor.
     * @param string $recipient
     */
    function __construct($recipient) {
        $message = "Email recipient: " . $recipient . " is invalid";
        parent::__construct($message);
    }
}
