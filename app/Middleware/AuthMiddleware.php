<?php

namespace App\Middleware;

use Core\AlertHelper;
use Core\Auth;
use Core\Language;
use Core\Middleware;
use Core\Session;

class AuthMiddleware extends Middleware
{
    private $auth;
    private $session;
    private $alertHelper;
    private $lang;

    public function __construct(Auth $auth, Session $session, AlertHelper $alertHelper, Language $lang) {
        $this->auth = $auth;
        $this->session = $session;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
    }

    public function before(): bool {
        $logged = $this->auth->isLogged();
        if (!$logged) $this->alertHelper->error($this->lang->get('alerts.login.failure'));
        // TODO: Proper way to check if user is logged
        // TODO: Something when user isn't logged
        return $logged;
    }

    public function after() {}
}