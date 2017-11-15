<?php

namespace Core;

use Core\Http\Request;

class Validator
{
    /** @var Request */
    private $request;

    /** @var array */
    private $errors;

    /** @var Language */
    private $lang;

    /**
     * Validator constructor.
     * @param Language $lang
     */
    function __construct(Language $lang) {
        $this->lang = $lang;
    }

    public function setRequest(Request $request) {
        $this->request = $request;
    }

    public function validate(): bool {
        $rules = $this->request->getValidationRules();
        if (empty($rules)) return true;

        $valid = true;
        foreach ($rules as $fieldName => $itemRules) {
            $languagePrefix = 'validation.' . $fieldName . '.';

            foreach ($itemRules as $itemRule) {
                $ruleParts = explode(":", $itemRule);
                $rule = $ruleParts[0];
                $input = [$this->request->getInput($fieldName)];

                $parameters = [];
                if (count($ruleParts) == 2) {
                    $parameters = explode(',', $ruleParts[1]);
                }

                $result = call_user_func_array([$this, $rule], array_merge($input, $parameters));

                // var_dump($result);
                if (!$result) {
                    $this->errors[$fieldName] []= $this->lang->get($languagePrefix . $rule, $parameters);
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

    public function minLen($item, $min): bool {
        return strlen($item) >= $min;
    }

    public function maxLen($item, $max): bool {
        return strlen($item) <= $max;
    }

    public function betweenLen($item, $min, $max): bool {
        return $this->minLen($item, $min) && $this->maxLen($item, $max);
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