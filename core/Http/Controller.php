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

    /**
     * @param $name
     * @param array $params
     * @return RedirectResponse
     */
    protected function route($name, $params = []) {
        return $this->responseFactory->redirectToRoute($name, $params);
    }

    /**
     * @param $name
     * @param $trip_id
     * @return RedirectResponse
     */
    protected function tripRoute($name, $trip_id) {
        return $this->responseFactory->redirectToTripRoute($name, $trip_id);
    }
}