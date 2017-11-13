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
        [
            id => [
                exists, min:10, max:255
                OR between:10,255
            ]
        ]
         */
        foreach ($this->rules as $item => $itemRules) {
            foreach ($itemRules as $itemRule) {
                // TODO: Validate
            }
        }

        return true;
    }

    public function min($item, $min) {}
    public function max($item, $max) {}
    public function between($item, $min, $max) {}
    public function exists($item, $table) {}
    public function unique($item, $table) {}
    public function email($item, $email) {}
    public function matches($item, $match) {}
}