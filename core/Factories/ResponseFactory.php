<?php

namespace Core\Factories;

use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;

class ResponseFactory
{
    private $di;

    public function __construct() {
        $this->di = di();
    }

    public function html($view, $data = [], $code = 200) {
        return new HtmlResponse($view, $data, $code);
    }

    public function json($data = [], $code = 200) {
        return new JsonResponse($data, $code);
    }

    public function redirect($path) {
        return new RedirectResponse($path);
    }

    public function redirectBack() {}
    public function redirectToRoute() {}

    public function error($code, $data = []) {
        return new HtmlResponse('error/main.html.twig', $data, $code);
    }
}