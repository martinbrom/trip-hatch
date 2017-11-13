<?php

namespace App\Middleware;

use Core\AlertHelper;
use Core\Auth;
use Core\Middleware;
use Core\Session;

class AuthMiddleware extends Middleware
{
    private $auth;
    private $session;
    private $alertHelper;

    public function __construct(Auth $auth, Session $session, AlertHelper $alertHelper) {
        $this->auth = $auth;
        $this->session = $session;
        $this->alertHelper = $alertHelper;
    }

    public function before(): bool {
        $logged = $this->session->exists('user');
        if (!$logged) $this->alertHelper->error('You are not logged!');
        // TODO: Proper way to check if user is logged
        // TODO: Something when user isn't logged
        return $logged;
    }

    public function after() {}
}