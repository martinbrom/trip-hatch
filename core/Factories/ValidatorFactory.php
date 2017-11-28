<?php

namespace Core\Factories;

use App\Repositories\ValidationRepository;
use Core\Language\Language;
use Core\Validation\Validator;

/**
 * Class ValidatorFactory
 * @package Core\Factories
 * @author Martin Brom
 */
class ValidatorFactory
{
    /** @var Language */
    private $language;

    /** @var ValidationRepository */
    private $validationRepository;

    /**
     * ValidatorFactory constructor.
     * @param Language $language
     * @param ValidationRepository $validationRepository
     */
    public function __construct(Language $language, ValidationRepository $validationRepository) {
        $this->language = $language;
        $this->validationRepository = $validationRepository;
    }

    /**
     * @param array $args
     * @return Validator
     */
    public function make($args = []): Validator {
        return new Validator($this->language, $this->validationRepository);
    }
}