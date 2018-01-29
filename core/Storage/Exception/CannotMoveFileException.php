<?php

namespace Core\Storage\Exception;

use Exception;

/**
 * Class CannotMoveFileException
 * @package Core\Storage\Exception
 * @author Martin Brom
 */
class CannotMoveFileException extends Exception
{
    /**
     * CannotMoveFileException constructor.
     * @param string $fileName
     */
    public function __construct($fileName) {
        $message = "File " . $fileName . " cannot be moved";
        parent::__construct($message);
    }
}
