<?php

namespace App\Controllers;

use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use Core\Auth;
use Core\Factories\ResponseFactory;
use Core\Http\Controller;
use Core\Http\Response\Response;
use Core\Language\Language;

class DayController extends Controller
{
    /** @var Auth */
    private $auth;

    /** @var DayRepository */
    private $dayRepository;

    /** @var TripRepository */
    private $tripRepository;

    /** @var Language */
    private $lang;

    /**
     * DayController constructor.
     * @param Auth $auth
     * @param DayRepository $dayRepository
     * @param TripRepository $tripRepository
     * @param Language $lang
     */
    public function __construct(Auth $auth, DayRepository $dayRepository, TripRepository $tripRepository, Language $lang) {
        $this->auth = $auth;
        $this->dayRepository = $dayRepository;
        $this->tripRepository = $tripRepository;
        $this->lang = $lang;
    }

    /**
     * @param $trip_id
     * @param $day_id
     * @return Response
     */
    public function delete($trip_id, $day_id) {
        if (!$this->dayRepository->tripHasDay($trip_id, $day_id))
            return $this->responseFactory->jsonAlert('Failure', 'error', 404);

        // TODO: Delete day and change trip end date and order of all days
        return $this->responseFactory->jsonAlert('Success', 'success', 200);
    }

    public function editModal($trip_id, $day_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip.missing'), 'error', 404);
        }

        $day = $this->dayRepository->getDay($day_id);
        return "aaaa";
    }
}