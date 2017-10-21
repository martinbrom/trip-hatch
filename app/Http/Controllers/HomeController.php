<?php

namespace App\Http\Controllers;

use \App\Core\Controller;

class HomeController extends Controller {
    public function index() {
        echo "landing page";
    }

    public function dashboard() {
        echo "dashboard";
    }
}