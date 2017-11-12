<?php

namespace Core;

// TODO: Add base messages for validation errors
class Validator
{
    private $rules = [];
    private $errors;

    // TODO: Dependencies
    function __construct() {}

    public function setRules($rules = []) {
        $this->rules = $rules;
    }

    public function validate(): bool {
        if (empty($this->rules)) return true;
        /*
        exists
        min:10
        max:255
        between:10,255
         */
        foreach ($this->rules as $rule) {
            $parts = explode(":", $rule);
            $parameters = count($parts) == 1 ? [] : explode(",", $parts[1]);
            if (!call_user_func($parts[0], $parameters)) return false;
        }

        return true;
    }

    public function min($min) {}
    public function max($max) {}
    public function between($min, $max) {}
    public function exists($table) {}
    public function unique($table) {}
    public function email($email) {}
    public function matches($item) {}
}