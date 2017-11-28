<?php

namespace Core;

/**
 * Class CsrfTokenHandler
 * @package Core
 * @author Martin Brom
 */
class CsrfTokenHandler
{
    /** @var Session */
    private $session;

    /**
     * CsrfTokenHandler constructor.
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    /**
     * @return bool
     */
    public function hasToken() {
        return $this->session->exists('csrf_token');
    }

    /**
     *
     */
    public function generate() {
        $this->session->set('csrf_token', token(64));
    }

    /**
     * @param $token
     * @return bool
     */
    public function matches($token) {
        return $token === $this->session->get('csrf_token');
    }

    /**
     * @return null
     */
    public function getToken() {
        if (!$this->hasToken())
            $this->generate();

        return $this->session->get('csrf_token');
    }
}