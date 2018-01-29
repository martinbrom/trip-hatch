<?php

namespace Core\Storage;

use Core\Config\Config;
use Core\Storage\Exception\CannotMoveFileException;
use Core\Storage\Exception\FileAlreadyExistsException;

class Storage
{
    /** @var string */
    private $tripFilesStoragePath;

    /**
     * Storage constructor.
     * @param Config $config
     */
    function __construct(Config $config) {
        $this->tripFilesStoragePath = $config->get('storage.trip_files');
    }

    /**
     * @param $file
     * @param null $fileName
     * @return null|string
     * @throws CannotMoveFileException
     * @throws FileAlreadyExistsException
     */
    public function store($file, $fileName = NULL) {
        if ($fileName == NULL)
            $fileName = token(32);

        $name = $file['name'];
        $path = $file['tmp_name'];
        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $fullName  = $fileName . "." . $extension;
        $dest = __DIR__ . "/../../" . $this->tripFilesStoragePath . "/" . $fullName;

        if (file_exists($dest))
            throw new FileAlreadyExistsException($name);

        if (!move_uploaded_file($path, $dest))
            throw new CannotMoveFileException($name);

        return $fullName;
    }
}
