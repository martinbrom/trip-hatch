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

        $valid = true;
        foreach ($rules as $fieldName => $itemRules) {
            foreach ($itemRules as $itemRule) {
                $ruleParts = explode(":", $itemRule);
                $rule = $ruleParts[0];
                $parameters = [$this->request->getData($fieldName)];

                if (count($ruleParts) == 2) {
                    unset($ruleParts[0]);
                    $constraints = explode(',', $ruleParts[1]);
                    $parameters = array_merge($parameters, $constraints);
                }

                $result = call_user_func_array([$this, $rule], $parameters);

                var_dump($result);
                if (!$result) {
                    $this->errors[$fieldName] []= call_user_func_array([$this, 'getErrorMessage'], array_merge([$rule, $fieldName], $constraints));
                    $valid = false;
                }
            }
        }

        return $valid;
    }

    // TODO: Until language is fully implemented
    public function getErrorMessage($rule, $fieldName, $parameter1 = null, $parameter2 = null) {
        $message = 'Field \'' . $fieldName . '\' must ';
        switch ($rule) {
            case 'min': $message .= 'be at most ' . $parameter1;break;
            case 'max': $message .= 'be at least ' . $parameter1;break;
            case 'between': $message .= 'be between ' . $parameter1 . ' and ' . $parameter2;break;
            case 'email': $message .= 'be a valid email address';break;
            case 'matches': $message .= 'match field ' . $parameter1;
        }

        return $message;
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

    public function required($item): bool {
        return $item != null;
    }

    public function getErrors() {
        return $this->errors;
    }
}