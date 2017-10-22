<?php

namespace App\Http\Controllers;

use \App\Core\Controller;
use \App\Core\View;

class HomeController extends Controller
{
    public function index() {
        View::render('home/index.html');
    }

    public function layout() {
        View::render('home/layout.html');
    }
}