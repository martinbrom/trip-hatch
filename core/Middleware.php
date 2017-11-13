<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response\Response;

/**
 * Interface defining Middleware class
 * Before function is run before request being processed,
 * and if all Middleware before functions complete without
 * error, request is processed and Middleware after
 * functions are ran after that.
 * @package Core
 * @author Martin Brom
 */
abstract class Middleware
{
    /** @var Response */
    protected $response;

    /** @var Request */
    protected $request;

    /**
     * Middleware method that is run after response is created
     * Returns whether the middleware ran without error
     * @return bool True if middleware ran without error, false otherwise
     */
    public abstract function before(): bool;

    /**
     * Middleware method that is run after response is created
     * @return void
     */
    public abstract function after();

    /**
     * @param Response $response
     */
    public function setResponse(Response $response) {
        $this->response = $response;
    }

    /**
     * @param Request $request
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }
}