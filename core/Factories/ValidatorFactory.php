<?php

namespace Core\Factories;

use Core\Language\Language;
use Core\Validation\Validator;

class ValidatorFactory
{
    private $language;

    public function __construct(Language $language) {
        $this->language = $language;
    }

    // TODO: Send validation rules here
    public function make($args = []): Validator {
        return new Validator($this->language);
    }
}