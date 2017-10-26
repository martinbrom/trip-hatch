<?php

namespace App\Controllers;

use \Core\Controller;
use \Core\View;

class HomeController extends Controller
{
    public function index() {
        View::render('home/index.html');
    }

    public function layout() {
        View::render('home/layout.html');
    }

    public function dashboard() {
        View::render('home/dashboard.html');
    }
}