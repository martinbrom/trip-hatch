<?php

namespace Core\Http\Response;

/**
 * Class RedirectResponse
 * @package Core\Http\Response
 * @author Martin Brom
 */
class RedirectResponse extends Response
{
    /**
     * RedirectResponse constructor.
     * @param $location
     */
    public function __construct($location) {
        $this->setCode(302);
        $this->addHeader('Location', $location);
        $this->addHeader('Connection', 'close');
    }

    /**
     * Doesn't get here because of location header
     */
    protected function sendContent() {}
}