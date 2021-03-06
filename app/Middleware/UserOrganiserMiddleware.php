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
 * Class UserOrganiserMiddleware
 * @package App\Middleware
 * @author Martin Brom
 */
class UserOrganiserMiddleware extends Middleware
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
     * UserOrganiserMiddleware constructor.
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
        if (!$this->auth->isOrganiser($trip_id)) {
            if ($this->request->isAjax())
                return $this->responseFactory->jsonAlert($this->lang->get('middleware.organiser.failure'), 'error', 401);

            $this->alertHelper->error($this->lang->get('middleware.organiser.failure'));
            return $this->responseFactory->redirectToTripRoute('show', $trip_id);
        }

        return null;
    }

    /**
     *
     */
    public function after() {
        if ($this->response instanceof HtmlResponse) {
            $this->response->addData('isOrganiser', true);
            $this->response->addData('isOwner', $this->auth->isOwner($this->request->getParameter('trip_id')));
        }
    }
}