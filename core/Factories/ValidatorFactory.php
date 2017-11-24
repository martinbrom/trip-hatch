<?php

namespace Core\Factories;

use App\Repositories\ValidationRepository;
use Core\Language\Language;
use Core\Validation\Validator;

class ValidatorFactory
{
    private $language;
    private $validationRepository;

    public function __construct(Language $language, ValidationRepository $validationRepository) {
        $this->language = $language;
        $this->validationRepository = $validationRepository;
    }

    public function make($args = []): Validator {
        return new Validator($this->language, $this->validationRepository);
    }
}