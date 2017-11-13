<?php

namespace Core\Http\Response;

use Core\View;

class HtmlResponse extends Response
{
    public function __construct($view, $data = [], $code = 200) {
        $this->addHeader('Content-type', 'text/html');
        $this->setContent($view);
        $this->setData($data);
        $this->setCode($code);
    }

    protected function sendContent() {
        View::render($this->getContent(), $this->getData());
    }
}