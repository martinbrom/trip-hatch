<?php

namespace Core\Http\Response;

class RedirectResponse extends Response
{
    public function __construct($location) {
        $this->header("Location: " . $location);
        $this->header("Connection: close");
    }

    // doesn't get here because of location header
    protected  function sendContent() {}
}