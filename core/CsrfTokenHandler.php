<?php

namespace Core;

class CsrfTokenHandler
{
    /** @var string */
    private $token;

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
        $token = token(64);
        $this->session->set('csrf_token', $token);
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