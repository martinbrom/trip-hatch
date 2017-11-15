<?php

namespace Core;

use Core\Http\Request;

/**
 * Class Validator
 * @package Core
 * @author Martin Brom
 */
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

    /**
     * @param Request $request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }

    /**
     * @return bool
     */
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
                if (count($ruleParts) == 2) $parameters = explode(',', $ruleParts[1]);

                $result = call_user_func_array([$this, $rule], array_merge($input, $parameters));

                if (!$result) {
                    $this->errors[$fieldName] []= $this->lang->get($languagePrefix . $rule, $parameters);
                    $valid = false;
                }
            }
        }

        return $valid;
    }

    /**
     * @param $item
     * @param $min
     * @return bool
     */
    public function min($item, $min): bool {
        return $item >= $min;
    }

    /**
     * @param $item
     * @param $max
     * @return bool
     */
    public function max($item, $max): bool {
        return $item <= $max;
    }

    /**
     * @param $item
     * @param $min
     * @param $max
     * @return bool
     */
    public function between($item, $min, $max): bool {
        return $this->min($item, $min) && $this->max($item, $max);
    }

    /**
     * @param $item
     * @param $min
     * @return bool
     */
    public function minLen($item, $min): bool {
        return strlen($item) >= $min;
    }

    /**
     * @param $item
     * @param $max
     * @return bool
     */
    public function maxLen($item, $max): bool {
        return strlen($item) <= $max;
    }

    /**
     * @param $item
     * @param $min
     * @param $max
     * @return bool
     */
    public function betweenLen($item, $min, $max): bool {
        return $this->minLen($item, $min) && $this->maxLen($item, $max);
    }

    /**
     * @param $item
     * @param $table
     * @return bool
     */
    public function exists($item, $table): bool {
        return true;
    }

    /**
     * @param $item
     * @param $table
     * @return bool
     */
    public function unique($item, $table): bool {
        return true;
    }

    /**
     * @param $item
     * @return bool
     */
    public function email($item): bool {
        return filter_var($item, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param $item
     * @param $match
     * @return bool
     */
    public function matches($item, $match): bool {
        return $item == $match;
    }

    /**
     * @param $item
     * @return bool
     */
    public function required($item): bool {
        return $item != null;
    }

    /**
     * @return array
     */
    public function getErrors(): array {
        return $this->errors;
    }
}