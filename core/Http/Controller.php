<?php

namespace Core\Http;

use Core\Factories\ResponseFactory;

abstract class Controller
{
    /** @var ResponseFactory */
    protected $responseFactory;

    public function __call($method, $args)  {
        if (!method_exists($this, $method)) {
            call_user_func_array([$this, $method], $args);
        } else {
            // TODO: Custom exception
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    public function setResponseFactory(ResponseFactory $responseFactory) {
        $this->responseFactory = $responseFactory;
    }

    protected function error($code, $data = []) {
        return $this->responseFactory->error($code, $data);
    }

    protected function redirect($location) {
        return $this->responseFactory->redirect($location);
    }
}