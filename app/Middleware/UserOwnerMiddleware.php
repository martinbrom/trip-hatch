<?php

namespace App\Middleware;

use Core\AlertHelper;
use Core\Auth;
use Core\Factories\ResponseFactory;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Middleware\Middleware;

/**
 * Class UserOwnerMiddleware
 * @package App\Middleware
 * @author Martin Brom
 */
class UserOwnerMiddleware extends Middleware
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
     * UserOwnerMiddleware constructor.
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
        $trip_id = $this->request->getParameter('trip_id');
        if (!$this->auth->isOwner($trip_id)) {
            if ($this->request->isAjax())
                return $this->responseFactory->jsonAlert($this->lang->get('middleware.owner.failure'), 'error', 401);

            $this->alertHelper->error($this->lang->get('middleware.owner.failure'));
            return $this->responseFactory->redirectToTripRoute('show', $trip_id);
        }

        return null;
    }

    /**
     *
     */
    public function after() {
        if ($this->response instanceof HtmlResponse) {
            $this->response->addData('isOwner', true);
            $this->response->addData('isOrganiser', true);
        }
    }
}