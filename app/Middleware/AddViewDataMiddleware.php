<?php

namespace App\Middleware;

use Core\Http\Response\HtmlResponse;
use Core\Language\Language;
use Core\Middleware\Middleware;
use Core\Session;

class AddViewDataMiddleware extends Middleware
{
    /** @var Session */
    private $session;

    /** @var Language */
    private $lang;

    /**
     * AddViewDataMiddleware constructor.
     * @param Session $session
     * @param Language $language
     */
    public function __construct(Session $session, Language $language) {
        $this->session = $session;
        $this->lang = $language;
    }

    public function before() { return null; }

    public function after() {
        if ($this->response instanceof HtmlResponse) {
            $this->response->addData('user', $this->session->get('user'));
            $this->response->addData('lang', $this->lang);
        }
    }
}