<?php

namespace App\Middleware;

use Core\Http\Response\HtmlResponse;
use Core\Middleware;
use Core\Session;

class AddViewDataMiddleware extends Middleware
{
    /** @var Session */
    private $session;

    /**
     * AddViewDataMiddleware constructor.
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    public function before(): bool { return true; }

    public function after() {
        if ($this->response instanceof HtmlResponse) {
            $this->response->addData('user', $this->session->get('user'));
        }
    }
}