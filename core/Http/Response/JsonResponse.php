<?php

namespace Core\Http\Response;

class JsonResponse extends Response
{
    public function __construct($content) {
        $this->header("Content-type: application/json");
        $this->setContent($content);
    }

    protected function sendContent() {
        echo \json_encode($this->getContent());
    }
}