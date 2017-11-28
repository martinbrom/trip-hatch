<?php

namespace Core\Validation\Exception;

use Exception;

/**
 * Class ValidationRuleNotExistsException
 * @package Core\Validation\Exception
 * @author Martin Brom
 */
class ValidationRuleNotExistsException extends Exception
{
    /**
     * ValidationRuleNotExistsException constructor.
     * @param string $rule
     */
    public function __construct($rule) {
        $message = "Validation rule " . $rule . " doesn't exist";
        parent::__construct($message);
    }
}
