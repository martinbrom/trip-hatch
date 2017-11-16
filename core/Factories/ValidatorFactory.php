<?php

namespace Core\Factories;

use Core\Language\Language;
use Core\Validation\Validator;

class ValidatorFactory implements Factory
{
    private $language;

    public function __construct(Language $language) {
        $this->language = $language;
    }

    public function make($args = []): Validator {
        return new Validator($this->language);
    }
}