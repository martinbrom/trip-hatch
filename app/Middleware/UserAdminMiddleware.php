<?php

namespace App\Middleware;

use Core\AlertHelper;
use Core\Auth;
use Core\Factories\ResponseFactory;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Middleware\Middleware;

/**
 * Class UserAdminMiddleware
 * @package App\Middleware
 * @author Martin Brom
 */
class UserAdminMiddleware extends Middleware
{
    /** @var Auth */
    private $auth;

    /** @var AlertHelper */
    private $alertHelper;

    /** @var Language */
    private $lang;

    /** @var ResponseFactory */
    private $responseFactory;

    /**
     * UserAdminMiddleware constructor.
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
        if (!$this->auth->isAdmin()) {
            if ($this->request->isAjax())
                return $this->responseFactory->json(['message' => $this->lang->get('middleware.admin.failure')], 401);

            $this->alertHelper->error($this->lang->get('middleware.admin.failure'));
            return $this->responseFactory->redirect('/trip/' . $this->request->getParameter('id'));
        }

        return null;
    }

    public function after() {}
}