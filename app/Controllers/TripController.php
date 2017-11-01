<?php

namespace App\Controllers;

use \Core\Controller;
use \Core\View;

class TripController extends Controller {
    private $tripRepository;

    function __construct(\App\Repositories\TripRepository $tripRepository) {
        $this->tripRepository = $tripRepository;
    }

    public function index() {
        // TODO: Take user_id from session
        $user_id = 4;
        $trips = $this->tripRepository->getTrips($user_id);
        echo '<pre>';
        var_dump($trips);
        echo '</pre>';
    }

    public function show($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            // TODO: Add popup that trip doesn't exist and return back
            echo "trip doesn't exist";
            return;
        }

        $days = $this->tripRepository->getDays($trip_id);
        View::render('trip/show.html.twig', [
            'days' => $days,
            'trip_title' => $trip['title']
        ]);
    }

    public function add() {
        View::render('trip/add.html.twig');
    }
}