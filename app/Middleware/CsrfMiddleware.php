<?php

namespace App\Middleware;

use Core\Factories\ResponseFactory;
use Core\Language\Language;
use Core\Middleware\Middleware;

class CsrfMiddleware extends Middleware
{
    /** @var ResponseFactory */
    private $responseFactory;

    /** @var Language */
    private $lang;

    /**
     * CsrfMiddleware constructor.
     * @param ResponseFactory $responseFactory
     * @param Language $language
     */
    public function __construct(ResponseFactory $responseFactory, Language $language) {
        $this->responseFactory = $responseFactory;
        $this->lang = $language;
    }

    public function before() {
        if ($this->request->getMethod() == 'GET')
            return null;

        $token = $this->request->getInput('_token') ?? $this->request->getHeader('X-CSRF-TOKEN');

        // if ($token && $this->csrf->matches($token))
        //     return null;

        $message = ['message' => $this->lang->get('middleware.csrf.invalid')];
        return ($this->request->isAjax()) ?
            $this->responseFactory->json($message, 401) :
            $this->responseFactory->error(401, $message);
    }

    public function after() {}
}