<?php

namespace Core\Mail\Exception;

/**
 * Class InvalidEmailSubjectException
 * @package Core\Mail\Exception
 * @author Martin Brom
 */
class InvalidEmailSubjectException extends EmailException
{
    /**
     * InvalidEmailSubjectException constructor.
     * @param string $subject
     */
    function __construct($subject) {
        $message = "Email subject: " . $subject . " is invalid";
        parent::__construct($message);
    }
}
