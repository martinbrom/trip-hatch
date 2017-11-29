<?php

namespace App\Controllers;

use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Response\JsonResponse;
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

    /**
     * @param $trip_id
     * @param $day_id
     * @return JsonResponse
     */
    public function editModal($trip_id, $day_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip.missing'), 'error', 404);
        }

        $day = $this->dayRepository->getDay($day_id);

        if ($day == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day.missing'), 'error', 404);
        }

        return $this->responseFactory->json(['day' => $day, 'trip' => $trip], 200);
    }

    /**
     * @param $trip_id
     * @param $day_id
     * @return JsonResponse
     */
    public function edit($trip_id, $day_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip.missing'), 'error', 404);
        }

        $day = $this->dayRepository->getDay($day_id);

        if ($day == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day.missing'), 'error', 404);
        }

        // TODO: File upload
        // TODO: If file input empty set default image id
        $image_id = 2;
        if (!$this->dayRepository->edit($day_id, $_POST['day_title'], $image_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day-edit.error'), 'danger', 500);
        }

        $day = $this->dayRepository->getDay($day_id);
        $html = $this->responseFactory->html('layouts/_day.html.twig', [
            'day' => $day, 'trip' => $trip,
            'isOrganiser' => $this->auth->isOrganiser($trip_id)
        ])->createContent();
        $data = [
            'type' => 'success',
            'message' => $this->lang->get('alerts.day-edit.success'),
            'day_id' => $day_id,
            'html' => $html
        ];
        return $this->responseFactory->json($data, 200);
    }
}