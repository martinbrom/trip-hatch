<?php

namespace Core\Http\Response;

class JsonResponse extends Response
{
    public function __construct($content = null, $code = 200) {
        $this->addHeader('Content-type', 'application/json');
        $this->setContent($content);
        $this->setCode($code);
    }

    protected function sendContent() {
        echo \json_encode($this->getContent());
    }
}