<?php

namespace App\Http\Controllers;

use \App\Core\Controller;
use \App\Core\View;

class HomeController extends Controller
{
    public function index() {
        View::render('home/index.html');
    }

    public function dashboard() {
        echo "dashboard";
    }
}