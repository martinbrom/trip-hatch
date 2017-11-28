<?php

namespace Core\Http\Response;

/**
 * Class Response
 * @package Core\Http\Response
 * @author Martin Brom
 */
abstract class Response
{
    /** @var */
    private $headers;

    /** @var */
    private $content;

    /** @var */
    private $data;

    /** @var int */
    private $code = 200;

    /**
     *
     */
    public function send() {
        \http_response_code($this->code);
        $this->sendHeaders();
        $this->sendContent();
    }

    /**
     *
     */
    protected function sendHeaders() {
        foreach ($this->headers as $header => $value) {
            \header($header . ': ' . $value);
        }
    }

    /**
     * @return mixed
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param $headers
     */
    public function setHeaders($headers) {
        $this->headers = [];
        foreach ($headers as $header => $value) {
            $this->addHeader($header, $value);
        }
    }

    /**
     * @param $header
     * @param $value
     */
    public function addHeader($header, $value) {
        $this->headers[$header] = $value;
    }

    /**
     * @return mixed
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param $content
     */
    public function setContent($content) {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getData() {
        return $this->data;
    }

    /**
     * @param $data
     */
    public function setData($data) {
        $this->data = $data;
    }

    /**
     * @param $key
     * @param $value
     */
    public function addData($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * @return int
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param $code
     */
    public function setCode($code) {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    protected abstract function sendContent();
}
