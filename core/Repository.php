<?php

namespace Core;

class Repository
{
    private $database;

    public function __construct(\Core\Database $database) {
        $this->database = $database;
    }

    public function getAll($table) {
        return $this->database->getAll($table);
    }
}