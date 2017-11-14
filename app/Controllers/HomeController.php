<?php

namespace App\Controllers;

use Core\Factories\ResponseFactory;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;

/**
 * Handles creating responses for pages related to the main page
 * such as frequently asked questions or terms and conditions
 * @package App\Controllers
 * @author Martin Brom
 */
class HomeController extends Controller
{
    /** @var ResponseFactory Instance for creating responses */
    private $responseFactory;

    /**
     * Creates new instance and injects response factory
     * @param ResponseFactory $responseFactory
     */
    function __construct(ResponseFactory $responseFactory) {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Returns a html response with a landing page content
     * @return HtmlResponse Landing page
     */
    public function index() {
        return $this->responseFactory->html('home/index.html');
    }

    /**
     * Returns a html response with a layout page content
     * @return HtmlResponse Page with layout
     */
    public function layout() {
        return $this->responseFactory->html('home/layout.html.twig');
    }

    public function testValidation($number) {
        return $this->responseFactory->html('home/layout.html.twig');
    }
}