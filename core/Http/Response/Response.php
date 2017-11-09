<?php

namespace Core\Http\Response;

abstract class Response
{
    private $headers;
    private $content;
    private $data;
    private $code = 200;

    public function send() {
        \http_response_code($this->code);
        $this->sendHeaders();
        $this->sendContent();
    }

    protected function header($header) {
        $this->headers[] = $header;
    }

    protected function sendHeaders() {
        foreach ($this->headers as $header) {
            \header($header);
        }
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    protected abstract function sendContent();
}