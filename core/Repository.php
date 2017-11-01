<?php

namespace Core;

class Repository
{
    private $database;

    public function __construct(\Core\Database $database) {
        $this->database = $database;
    }

    public function fetchAll($query, $data = []) {
        return $this->database->query($query, $data)->fetchAll();
    }

    public function fetch($query, $data = []) {
        return $this->database->query($query, $data)->fetch();
    }
}