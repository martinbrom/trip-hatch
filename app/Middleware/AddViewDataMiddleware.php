<?php

namespace App\Middleware;

use Core\Http\Response\HtmlResponse;
use Core\Middleware\Middleware;
use Core\Session;

class AddViewDataMiddleware extends Middleware
{
    /** @var Session */
    private $session;

    /**
     * AddViewDataMiddleware constructor.
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    /**
     * @return null
     */
    public function before() { return null; }

    /**
     *
     */
    public function after() {
        if ($this->response instanceof HtmlResponse) {
            $this->response->addData('user', $this->session->get('user'));
            $this->response->addData('validation_errors', $this->session->pop('validation_errors'));
            $this->response->addData('input', $this->session->pop('input'));
        }
    }
}