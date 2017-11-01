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
        View::render('trip/index.html.twig', [
            'trips' => $trips
        ]);
    }

    public function create() {
        View::render('trip/create.html.twig');
    }

    public function store() {}

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
    
    public function edit() {}
    public function update() {}
    public function destroy() {}

    public function actions($day_id) {
        // TODO: AJAX request return and then display on page using JS
        $actions = $this->tripRepository->getActions($day_id);
        var_dump($actions);
    }
}