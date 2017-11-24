<?php

namespace Core\Validation;

use App\Repositories\ValidationRepository;
use Core\Http\Request;
use Core\Language\Language;
use Core\Validation\Exception\ValidationRuleNotExistsException;

/**
 * Handles request validating, runs validation rules
 * from request route on input data from request and
 * fetches translated validation errors from Language instance
 * @package Core\Validation
 * @author Martin Brom
 */
class Validator
{
    /** @var Request Validated request */
    private $request;

    /** @var array Validation error messages */
    private $errors;

    /** @var Language Dictionary of translations */
    private $lang;

    /** @var ValidationRepository */
    private $validationRepository;

    /**
     * Creates new instance and injects language instance
     * @param Language $lang Language instance
     * @param ValidationRepository $validationRepository
     */
    function __construct(Language $lang, ValidationRepository $validationRepository) {
        $this->lang = $lang;
        $this->validationRepository = $validationRepository;
    }

    /**
     * Adds a reference to request, that is being validated
     * to get input from
     * @param Request $request Validated request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }

    /**
     * Runs all validation rules and returns whether
     * all run without error
     * @return bool True if all validation rules passed, false otherwise
     * @throws ValidationRuleNotExistsException When a non-existent validation rule is called
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

                if (!method_exists($this, $rule)) throw new ValidationRuleNotExistsException();

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
     * Checks whether item is greater or equal to min value
     * @param mixed $item Validated input
     * @param int $min Minimal value
     * @return bool True if item is greater or equal to min, false otherwise
     */
    public function min($item, $min): bool {
        return $item >= $min;
    }

    /**
     * Checks whether item is smaller or equal to max value
     * @param mixed $item Validated input
     * @param int $max Maximal value
     * @return bool True if item is smaller or equal to max, false otherwise
     */
    public function max($item, $max): bool {
        return $item <= $max;
    }

    /**
     * Checks whether item is greater or equal to min value and
     * whether item is smaller or equal to max value at the same time
     * @param mixed $item Validated input
     * @param int $min Minimal value
     * @param int $max Maximal value
     * @return bool True if item is greater or equal to min
     *              and smaller or equal to max, false otherwise
     */
    public function between($item, $min, $max): bool {
        return $this->min($item, $min) && $this->max($item, $max);
    }

    /**
     * Checks whether length of item is greater or equal to min
     * @param mixed $item Validated input
     * @param int $min Minimal length
     * @return bool True if item length is greater or equal to min, false otherwise
     */
    public function minLen($item, $min): bool {
        return strlen($item) >= $min;
    }

    /**
     * Checks whether length of item is smaller or equal to max
     * @param mixed $item Validated input
     * @param int $max Maximal length
     * @return bool True if item length is smaller or equal to max, false otherwise
     */
    public function maxLen($item, $max): bool {
        return strlen($item) <= $max;
    }

    /**
     * Checks whether length of item is greater or equal to min and
     * whether length of item is smaller or equal to max at the same time
     * @param mixed $item Validated input
     * @param int $min Minimal length
     * @param int $max Maximal length
     * @return bool True if item length is greater or equal to min
     *              and smaller or equal to max, false otherwise
     */
    public function betweenLen($item, $min, $max): bool {
        return $this->minLen($item, $min) && $this->maxLen($item, $max);
    }

    /**
     * Checks whether item exists in a given database table and column
     * @param mixed $item Validated input
     * @param string $table Name of database table
     * @param string $column Name of column in table
     * @return bool True if item exists in database, false otherwise
     */
    public function exists($item, $table, $column): bool {
        return $this->validationRepository->exists($item, $table, $column);
    }

    /**
     * Checks whether item doesn't exist in a given database table and column
     * @param mixed $item Validated input
     * @param string $table Name of database table
     * @param string $column Name of column in table
     * @return bool True if item doesn't exist in database, false otherwise
     */
    public function unique($item, $table, $column): bool {
        return $this->validationRepository->unique($item, $table, $column);
    }

    /**
     * Checks whether item is a valid email address
     * @param mixed $item Validated input
     * @return bool True if item is a valid email address, false otherwise
     */
    public function email($item): bool {
        return filter_var($item, FILTER_VALIDATE_EMAIL);
    }

    /**
     * Checks whether item is the same as match
     * @param mixed $item Validated input
     * @param string $match Name of input to match to
     * @return bool True if both inputs match, false otherwise
     */
    public function matches($item, $match): bool {
        return $item == $this->request->getInput($match);
    }

    /**
     * Checks whether item is present in input
     * @param mixed $item Validated input
     * @return bool True if input is present, false otherwise
     */
    public function required($item): bool {
        return $item != null;
    }

    /**
     * Returns all validation errors
     * @return array Validation errors
     */
    public function getErrors(): array {
        return $this->errors;
    }
}