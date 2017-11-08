<?php

namespace App\Controllers;

use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;

class HomeController extends Controller
{
    public function index() {
        return new HtmlResponse('home/index.html');
    }

    public function layout() {
        return new HtmlResponse('home/layout.html.twig');
    }

    public function dashboard() {
        return new HtmlResponse('home/dashboard.html.twig', ['stuff' => 'value']);
    }

    public function testMiddleware() {
        return new JsonResponse('json response content');
    }
}