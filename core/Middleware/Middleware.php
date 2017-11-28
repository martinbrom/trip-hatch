<?php

namespace Core\Middleware;

use Core\Http\Request;
use Core\Http\Response\Response;

/**
 * Definition of middleware classes
 * Before function is run before request being processed,
 * and if all Middleware before functions complete without
 * error, request is processed and Middleware after
 * functions are ran after that.
 * @package Core
 * @author Martin Brom
 */
abstract class Middleware
{
    /** @var Response A response created by processing request */
    protected $response;

    /** @var Request Incoming request to check */
    protected $request;

    /**
     * Middleware method that is run before request response is created
     * Returns a response if middleware failed, null otherwise
     * @return Response|null A response if middleware failed, null otherwise
     */
    public abstract function before();

    /**
     * Middleware method that is run after response is created
     * @return void
     */
    public abstract function after();

    /**
     * Sets a response created by processing request
     * @param Response $response Response created by processing request
     */
    public function setResponse(Response $response) {
        $this->response = $response;
    }

    /**
     * Sets an incoming request to check
     * @param Request $request Incoming request to check
     */
    public function setRequest(Request $request) {
        $this->request = $request;
    }
}