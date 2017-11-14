<?php

namespace Core;

// TODO: Add base messages for validation errors
use Core\Http\Request;

class Validator
{
    /** @var Request */
    private $request;
    private $errors;

    // TODO: Dependencies
    function __construct() {}

    public function setRequest(Request $request) {
        $this->request = $request;
    }

    public function validate(): bool {
        $rules = $this->request->getValidationRules();
        if (empty($rules)) return true;
        /*
        [
            id => [
                exists, min:10, max:255
                OR between:10,255
            ]
        ]
         */

        $valid = true;
        foreach ($rules as $fieldName => $itemRules) {
            // var_dump($item); var_dump($itemRules);
            foreach ($itemRules as $itemRule) {
                $ruleParts = explode(":", $itemRule);
                $rule = $ruleParts[0];
                $parameters = $this->request->getData($fieldName);
                if (count($ruleParts) == 2) {
                    unset($ruleParts[0]);
                    $parameters = array_merge([$parameters], explode(',', $ruleParts[1]));
                }
                $result = call_user_func_array([$this, $rule], $parameters);
                var_dump($result);
                if (!$result) {
                    // echo sprintf("%s must be between %d and %d!", $fieldName, $parameters[1], $parameters[2]);
                    $valid = false;
                }
            }
        }

        return $valid;
    }

    public function min($item, $min): bool {
        return $item >= $min;
    }

    public function max($item, $max): bool {
        return $item <= $max;
    }

    public function between($item, $min, $max): bool {
        return $this->min($item, $min) && $this->max($item, $max);
    }

    public function exists($item, $table): bool { return true; }
    public function unique($item, $table): bool { return true; }

    public function email($item): bool {
        return filter_var($item, FILTER_VALIDATE_EMAIL);
    }

    public function matches($item, $match): bool {
        return $item == $match;
    }

    public function notnull($item): bool {
        return $item != null;
    }
}