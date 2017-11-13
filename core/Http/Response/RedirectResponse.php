<?php

namespace Core\Http\Response;

class RedirectResponse extends Response
{
    public function __construct($location) {
        $this->setCode(302);
        $this->addHeader('Location', $location);
        $this->addHeader('Connection', 'close');
    }

    // doesn't get here because of location header
    protected function sendContent() {}
}