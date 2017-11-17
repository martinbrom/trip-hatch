<?php

namespace Core\Http;

use App\Exception\MethodNotFoundException;
use Core\Factories\ResponseFactory;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\RedirectResponse;

/**
 * Class Controller
 * @package Core\Http
 * @author Martin Brom
 */
abstract class Controller
{
    /** @var ResponseFactory */
    protected $responseFactory;

    /**
     * @param $method
     * @param $args
     * @throws MethodNotFoundException
     */
    public function __call($method, $args)  {
        if (!method_exists($this, $method)) {
            call_user_func_array([$this, $method], $args);
        } else {
            throw new MethodNotFoundException($method, get_class($this));
        }
    }

    /**
     * @param ResponseFactory $responseFactory
     */
    public function setResponseFactory(ResponseFactory $responseFactory) {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param $code
     * @param array $data
     * @return HtmlResponse
     */
    protected function error($code, $data = []) {
        return $this->responseFactory->error($code, $data);
    }

    /**
     * @param $location
     * @return RedirectResponse
     */
    protected function redirect($location) {
        return $this->responseFactory->redirect($location);
    }
}