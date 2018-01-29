<?php

namespace Core\Storage\Exception;

use Exception;

/**
 * Class FileAlreadyExistsException
 * @package Core\Storage\Exception
 * @author Martin Brom
 */
class FileAlreadyExistsException extends Exception
{
    /**
     * FileAlreadyExistsException constructor.
     * @param string $fileName
     */
    function __construct($fileName) {
        $message = "File " . $fileName . " cannot be saved because it already exists";
        parent::__construct($message);
    }
}
