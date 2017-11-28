<?php

namespace Core\Factories;

use Core\DependencyInjector\DependencyInjector;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
use Core\Routing\RouteHelper;

class ResponseFactory
{
    /** @var DependencyInjector */
    private $di;

    /** @var HtmlResponseFactory */
    private $htmlResponseFactory;

    /** @var RouteHelper */
    private $routeHelper;

    public function __construct(DependencyInjector $di, HtmlResponseFactory $htmlResponseFactory, RouteHelper $routeHelper) {
        $this->di = $di;
        $this->htmlResponseFactory = $htmlResponseFactory;
        $this->routeHelper = $routeHelper;
    }

    public function html($template, $data = [], $code = 200) {
        return $this->htmlResponseFactory->make($template, $data, $code);
    }

    public function json($data = [], $code = 200) {
        return new JsonResponse($data, $code);
    }

    public function jsonAlert($message, $type = 'info', $code = 200) {
        return $this->json(['type' => $type, 'message' => $message], $code);
    }

    public function redirect($path) {
        return new RedirectResponse($path);
    }

    public function redirectBack() {}

    public function redirectToRoute($routeName, $params = []) {
        $path = $this->routeHelper->get($routeName, $params);
        return $this->redirect($path);
    }

    public function redirectToTripRoute($routeName, $trip_id) {
        return $this->redirectToRoute('trip.' . $routeName, ['trip_id' => $trip_id]);
    }

    public function error(int $code, $data = []) {
        $separateErrorPages = [404, 500, 401];
        $page = in_array($code, $separateErrorPages) ? $code : 'index';
        return $this->htmlResponseFactory->make('error/' . $page . '.html.twig', $data, $code);
    }
}