<?php

namespace App\Controllers;

use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;

/**
 * Handles creating responses for pages related to the main page
 * such as frequently asked questions or terms and conditions
 * @package App\Controllers
 * @author Martin Brom
 */
class HomeController extends Controller
{
    /**
     * Returns a html response with a landing page content
     * @return HtmlResponse Landing page
     */
    public function index() {
        return new HtmlResponse('home/index.html');
    }

    /**
     * Returns a html response with a layout page content
     * @return HtmlResponse Page with layout
     */
    public function layout() {
        return new HtmlResponse('home/layout.html.twig');
    }

    public function testMiddleware() {
        return new JsonResponse('json response content');
    }
}