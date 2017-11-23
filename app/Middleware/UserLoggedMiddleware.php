<?php

namespace App\Middleware;

use Core\AlertHelper;
use Core\Auth;
use Core\Factories\ResponseFactory;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Middleware\Middleware;

/**
 * Class UserLoggedMiddleware
 * @package App\Middleware
 * @author Martin Brom
 */
class UserLoggedMiddleware extends Middleware
{
    /** @var Auth  */
    private $auth;

    /** @var AlertHelper  */
    private $alertHelper;

    /** @var Language  */
    private $lang;

    /** @var ResponseFactory  */
    private $responseFactory;

    /**
     * UserLoggedMiddleware constructor.
     * @param Auth $auth
     * @param AlertHelper $alertHelper
     * @param Language $lang
     * @param ResponseFactory $responseFactory
     */
    public function __construct(Auth $auth, AlertHelper $alertHelper, Language $lang, ResponseFactory $responseFactory) {
        $this->auth = $auth;
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
                return $this->responseFactory->json(['message' => $this->lang->get('middleware.logged.failure')], 401);

            $this->alertHelper->error($this->lang->get('middleware.logged.failure'));
            return $this->responseFactory->redirectToRoute('login');
        }

        return null;
    }

    public function after() {}
}