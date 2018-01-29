<?php

namespace Core\Storage;

use Core\Config\Config;
use Core\Storage\Exception\CannotMoveFileException;
use Core\Storage\Exception\FileAlreadyExistsException;

class Storage
{
    /** @var Config */
    private $config;

    /**
     * Storage constructor.
     * @param Config $config
     */
    function __construct(Config $config) {
        $this->config = $config;
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
        $dest = __DIR__ . "/../../public/storage/files/" . $fileName . "." . $extension;

        if (file_exists($dest))
            throw new FileAlreadyExistsException($name);

        if (!move_uploaded_file($path, $dest))
            throw new CannotMoveFileException($name);

        return $fileName;
    }
}
