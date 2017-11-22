<?php

namespace App\Controllers;

use Core\Auth;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\Response;

/**
 * Handles creating responses for pages related to the main page
 * such as frequently asked questions or terms and conditions
 * @package App\Controllers
 * @author Martin Brom
 */
class HomeController extends Controller
{
    /** @var Auth Instance for checking user logged-in state */
    private $auth;

    /**
     * HomeController constructor.
     * @param Auth $auth
     */
    function __construct(Auth $auth) {
        $this->auth = $auth;
    }

    /**
     * Returns a html response with a landing page content
     * or redirects to dashboard if user is logged
     * @return Response Landing page
     */
    public function index() {
        if ($this->auth->isLogged())
            return $this->responseFactory->redirect('/trips');
        return $this->responseFactory->html('home/index.html');
    }

    /**
     * Returns a html response with a layout page content
     * @return HtmlResponse Page with layout
     */
    public function layout() {
        return $this->responseFactory->html('home/layout.html.twig');
    }

    public function testValidation() {
        return $this->responseFactory->html('home/layout.html.twig');
    }
}