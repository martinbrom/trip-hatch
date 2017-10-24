<?php

namespace App\Models;

use Core\Model;

class TestModel extends Model {
    private $id;
    private $name;

    public function __construct($parameters = []) {
        foreach ($parameters as $key => $value) {
            $this->$key = $value;
        }
    }
}