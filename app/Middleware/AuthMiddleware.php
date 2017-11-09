<?php

namespace App\Middleware;

use Core\Auth;
use Core\Middleware;
use Core\Session;

class AuthMiddleware implements Middleware
{
    private $auth;
    private $session;

    public function __construct(Auth $auth, Session $session) {
        $this->auth = $auth;
        $this->session = $session;
    }

    public function before(): bool {
        return $this->session->exists('user');
        // TODO: Proper way to check if user is logged
        // TODO: Something when user isn't logged
    }

    public function after() {}
}