<?php

namespace App\Middleware;

use Core\Http\Response\HtmlResponse;
use Core\Middleware\Middleware;
use Core\Session;

class AddAlertsMiddleware extends Middleware
{
    /** @var Session */
    private $session;

    /**
     * AddAlertsMiddleware constructor.
     * @param Session $session
     */
    public function __construct(Session $session) {
        $this->session = $session;
    }

    public function before() { return null; }

    public function after() {
        if ($this->response instanceof HtmlResponse) {
            $alerts = $this->session->get('alerts');
            $this->session->delete('alerts');
            $this->response->addData('alerts', $alerts);
        }

        // var_dump($this->response->getData()['alerts']);
    }
}