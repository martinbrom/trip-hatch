<?php

namespace App\Controllers;

use \Core\Controller;
use \Core\View;

class TripController extends Controller {
    public function show($id) {
        // TODO: get trip with id
        View::render('trip/show.html');
    }

    public function add() {
        View::render('trip/add.html');
    }
}