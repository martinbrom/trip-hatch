<?php

namespace Core\Factories;

use App\Repositories\ValidationRepository;
use Core\Auth;
use Core\Language\Language;
use Core\Session;
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

    /** @var Auth */
    private $auth;

    /** @var Session */
    private $session;

    /**
     * ValidatorFactory constructor.
     * @param Language $language
     * @param ValidationRepository $validationRepository
     * @param Auth $auth
     * @param Session $session
     */
    public function __construct(Language $language, ValidationRepository $validationRepository, Auth $auth, Session $session) {
        $this->language = $language;
        $this->validationRepository = $validationRepository;
        $this->auth = $auth;
        $this->session = $session;
    }

    /**
     * @param array $args
     * @return Validator
     */
    public function make($args = []): Validator {
        return new Validator($this->language, $this->validationRepository, $this->auth, $this->session);
    }
}