<?php

namespace App\Controllers;

use \Core\Controller;
use \Core\View;

class TripController extends Controller {
    private $tripRepository;

    function __construct(\App\Repositories\TripRepository $tripRepository) {
        $this->tripRepository = $tripRepository;
    }

    public function show($id) {
        $data = $this->tripRepository->first($id);
        var_dump($data);
        View::render('trip/show.html');
    }

    public function add() {
        View::render('trip/add.html');
    }
}