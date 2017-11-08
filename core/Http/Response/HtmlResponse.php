<?php

namespace Core\Http\Response;

use Core\View;

class HtmlResponse extends Response
{
    public function __construct($content, $data = []) {
        $this->header("Content-type: text/html");
        $this->setContent($content);
        $this->setData($data);
    }

    protected function sendContent() {
        View::render($this->getContent(), $this->getData());
    }
}