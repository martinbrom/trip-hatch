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

    protected function sendHeaders() {
        foreach ($this->headers as $header => $value) {
            \header($header . ': ' . $value);
        }
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function setHeaders($headers) {
        $this->headers = [];
        foreach ($headers as $header => $value) {
            $this->addHeader($header, $value);
        }
    }

    public function addHeader($header, $value) {
        $this->headers[$header] = $value;
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

    public function addData($key, $value) {
        $this->data[$key] = $value;
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode($code) {
        $this->code = $code;
    }

    protected abstract function sendContent();
}
