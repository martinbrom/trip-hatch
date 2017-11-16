<?php

namespace App\Middleware;

use Core\AlertHelper;
use Core\Auth;
use Core\Factories\ResponseFactory;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Middleware\Middleware;
use Core\Session;

/**
 * Class AuthMiddleware
 * @package App\Middleware
 * @author Martin Brom
 */
class AuthMiddleware extends Middleware
{
    /** @var Auth  */
    private $auth;

    /** @var Session  */
    private $session;

    /** @var AlertHelper  */
    private $alertHelper;

    /** @var Language  */
    private $lang;

    /** @var ResponseFactory  */
    private $responseFactory;


    /**
     * AuthMiddleware constructor.
     * @param Auth $auth
     * @param Session $session
     * @param AlertHelper $alertHelper
     * @param Language $lang
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Auth $auth, Session $session, AlertHelper $alertHelper, Language $lang, ResponseFactory $responseFactory) {
        $this->auth = $auth;
        $this->session = $session;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @return Response|null
     */
    public function before() {
        if (!$this->auth->isLogged()) {
            if ($this->request->isAjax())
                return $this->responseFactory->json(['message' => $this->lang->get('middleware.auth.invalid')], 401);

            $this->alertHelper->error($this->lang->get('alerts.login.failure'));
            return $this->responseFactory->redirect('/login');
        }

        return null;
    }

    /**
     *
     */
    public function after() {}
}