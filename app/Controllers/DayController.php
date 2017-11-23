<?php

namespace App\Controllers;

use App\Repositories\DayRepository;
use Core\Auth;
use Core\Factories\ResponseFactory;
use Core\Http\Controller;
use Core\Http\Response\Response;

class DayController extends Controller
{
    /** @var Auth */
    private $auth;

    /** @var DayRepository */
    private $dayRepository;

    /**
     * DayController constructor.
     * @param Auth $auth
     * @param DayRepository $dayRepository
     */
    public function __construct(Auth $auth, DayRepository $dayRepository) {
        $this->auth = $auth;
        $this->dayRepository = $dayRepository;
    }

    /**
     * @param $trip_id
     * @param $day_id
     * @return Response
     */
    public function delete($trip_id, $day_id) {
        if (!$this->dayRepository->tripHasDay($trip_id, $day_id))
            return $this->responseFactory->json(['message' => 'Failure']);

        // TODO: Delete day and change trip end date and order of all days
        return $this->responseFactory->json(['message' => 'Success']);
    }
}