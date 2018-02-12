<?php

namespace App\Middleware;

use Core\Auth;
use Core\Http\Response\HtmlResponse;
use Core\Middleware\Middleware;
use Core\Session;

/**
 * Class AddViewDataMiddleware
 * @package App\Middleware
 * @author Martin Brom
 */
class AddViewDataMiddleware extends Middleware
{
    /** @var Session */
    private $session;

    /** @var Auth */
    private $auth;

    /**
     * AddViewDataMiddleware constructor.
     * @param Session $session
     * @param Auth $auth
     */
    public function __construct(Session $session, Auth $auth) {
        $this->session = $session;
        $this->auth = $auth;
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
            $this->auth->updateUserData();
            $this->response->addData('user', $this->session->get('user'));
            $this->response->addData('validation_errors', $this->session->pop('validation_errors'));
            $this->response->addData('input', $this->session->pop('input'));
        }
    }
}