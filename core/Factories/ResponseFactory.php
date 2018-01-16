<?php

namespace Core\Factories;

use Core\DependencyInjector\DependencyInjector;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
use Core\Routing\RouteHelper;

/**
 * Class ResponseFactory
 * @package Core\Factories
 * @author Martin Brom
 */
class ResponseFactory
{
    /** @var DependencyInjector */
    private $di;

    /** @var HtmlResponseFactory */
    private $htmlResponseFactory;

    /** @var RouteHelper */
    private $routeHelper;

    /**
     * ResponseFactory constructor.
     * @param DependencyInjector $di
     * @param HtmlResponseFactory $htmlResponseFactory
     * @param RouteHelper $routeHelper
     */
    public function __construct(DependencyInjector $di, HtmlResponseFactory $htmlResponseFactory, RouteHelper $routeHelper) {
        $this->di = $di;
        $this->htmlResponseFactory = $htmlResponseFactory;
        $this->routeHelper = $routeHelper;
    }

    /**
     * @param $template
     * @param array $data
     * @param int $code
     * @return HtmlResponse
     */
    public function html($template, $data = [], $code = 200) {
        return $this->htmlResponseFactory->make($template, $data, $code);
    }

    /**
     * @param array $data
     * @param int $code
     * @return JsonResponse
     */
    public function json($data = [], $code = 200) {
        return new JsonResponse($data, $code);
    }

    /**
     * @param $message
     * @param string $type
     * @param int $code
     * @return JsonResponse
     */
    public function jsonAlert($message, $type = 'info', $code = 200) {
        return $this->json(['type' => $type, 'message' => $message], $code);
    }

    /**
     * @param $path
     * @return RedirectResponse
     */
    public function redirect($path) {
        return new RedirectResponse($path);
    }

    /**
     * @return RedirectResponse
     */
    public function redirectBack() {
        $path = $_SERVER['HTTP_REFERER'];

        return $path == '' ? $this->redirectToRoute('dashboard') : $this->redirect($path);
    }

    /**
     * @param $routeName
     * @param array $params
     * @return RedirectResponse
     */
    public function redirectToRoute($routeName, $params = []) {
        $path = $this->routeHelper->get($routeName, $params);
        return $this->redirect($path);
    }

    /**
     * @param $routeName
     * @param $trip_id
     * @return RedirectResponse
     */
    public function redirectToTripRoute($routeName, $trip_id) {
        return $this->redirectToRoute('trip.' . $routeName, ['trip_id' => $trip_id]);
    }

    /**
     * @param int $code
     * @param array $data
     * @return HtmlResponse
     */
    public function error(int $code, $data = []) {
        $separateErrorPages = [404, 500, 401];
        $page = in_array($code, $separateErrorPages) ? $code : 'index';
        return $this->htmlResponseFactory->make('error/' . $page . '.html.twig', $data, $code);
    }
}