<?php

namespace App\Middleware;

use Core\CsrfTokenHandler;
use Core\Factories\ResponseFactory;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Middleware\Middleware;

/**
 * Class CsrfMiddleware
 * @package App\Middleware
 * @author Martin Brom
 */
class CsrfMiddleware extends Middleware
{
    /** @var ResponseFactory */
    private $responseFactory;

    /** @var Language */
    private $lang;

    /** @var CsrfTokenHandler */
    private $tokenHandler;

    /**
     * CsrfMiddleware constructor.
     * @param CsrfTokenHandler $csrfTokenHandler
     * @param ResponseFactory $responseFactory
     * @param Language $language
     */
    public function __construct(CsrfTokenHandler $csrfTokenHandler, ResponseFactory $responseFactory, Language $language) {
        $this->responseFactory = $responseFactory;
        $this->lang = $language;
        $this->tokenHandler = $csrfTokenHandler;

        if (!$this->tokenHandler->hasToken())
            $this->tokenHandler->generate();
    }

    /**
     * @return Response|null
     */
    public function before() {
        if ($this->request->getMethod() == 'GET')
            return null;

        $token = $this->request->getInput('csrf_token') ?? $this->request->getHeader('X-CSRF-TOKEN');

        if ($token && $this->tokenHandler->matches($token))
            return null;

        $message = ['message' => $this->lang->get('middleware.csrf.invalid')];
        return ($this->request->isAjax()) ?
            $this->responseFactory->json($message, 401) :
            $this->responseFactory->error(401, $message);
    }

    /**
     *
     */
    public function after() {
        $this->response->addData('csrf_token', di(\Core\Session::class)->get('csrf_token'));
    }
}