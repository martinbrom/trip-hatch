<?php

namespace Core\Factories;

use Core\DependencyInjector;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;

class ResponseFactory
{
    /** @var DependencyInjector */
    private $di;

    /** @var HtmlResponseFactory */
    private $htmlResponseFactory;

    public function __construct(DependencyInjector $di, HtmlResponseFactory $htmlResponseFactory) {
        $this->di = $di;
        $this->htmlResponseFactory = $htmlResponseFactory;
    }

    public function html($template, $data = [], $code = 200) {
        return $this->htmlResponseFactory->make($template, $data, $code);
    }

    public function json($data = [], $code = 200) {
        return new JsonResponse($data, $code);
    }

    public function redirect($path) {
        return new RedirectResponse($path);
    }

    public function redirectBack() {}
    public function redirectToRoute() {}

    public function error(int $code, $data = []) {
        return $this->htmlResponseFactory->make('error/index.html.twig', $data, $code);
    }
}