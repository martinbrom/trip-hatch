<?php

namespace App\Middleware;

use Core\AlertHelper;
use Core\Auth;
use Core\Factories\ResponseFactory;
use Core\Language\Language;
use Core\Middleware\Middleware;
use Core\Session;

class AuthMiddleware extends Middleware
{
    private $auth;
    private $session;
    private $alertHelper;
    private $lang;
    private $responseFactory;

    public function __construct(Auth $auth, Session $session, AlertHelper $alertHelper, Language $lang, ResponseFactory $responseFactory) {
        $this->auth = $auth;
        $this->session = $session;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
        $this->responseFactory = $responseFactory;
    }

    public function before() {
        if (!$this->auth->isLogged()) {
            if ($this->request->isAjax())
                return $this->responseFactory->json(['message' => $this->lang->get('middleware.auth.invalid')], 401);

            $this->alertHelper->error($this->lang->get('alerts.login.failure'));
            return $this->responseFactory->redirect('/login');
        }

        return null;
    }

    public function after() {}
}