<?php

namespace App\Controllers;

use App\Repositories\ActionRepository;
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

    /** @var ActionRepository */
    private $actionRepository;

    /**
     * DayController constructor.
     * @param Auth $auth
     * @param DayRepository $dayRepository
     * @param TripRepository $tripRepository
     * @param Language $lang
     * @param ActionRepository $actionRepository
     */
    public function __construct(
            Auth $auth,
            DayRepository $dayRepository,
            TripRepository $tripRepository,
            Language $lang,
            ActionRepository $actionRepository) {
        $this->auth = $auth;
        $this->dayRepository = $dayRepository;
        $this->tripRepository = $tripRepository;
        $this->lang = $lang;
        $this->actionRepository = $actionRepository;
    }

    /**
     * @param $trip_id
     * @param $day_id
     * @return Response
     */
    public function delete($trip_id, $day_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip.missing'), 'error', 404);
        }

        $day = $this->dayRepository->getDay($day_id);

        if ($day == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day.missing'), 'error', 404);
        }

        // TODO: Deleting day with actions
        if (!$this->dayRepository->delete($day_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day-delete.error'), 'error', 500);
        }

        // TODO: Delete day and change trip end date and order of all days
        $data = [
            'message' => $this->lang->get('alerts.day-delete.success', [$day['title']]),
            'type' => 'success',
            'day_id' => $day_id
        ];
        return $this->responseFactory->json($data, 200);
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

    /**
     * @param $trip_id
     * @param $day_id
     * @return JsonResponse
     */
    public function addAction($trip_id, $day_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip.missing'), 'error', 404);
        }

        $day = $this->dayRepository->getDay($day_id);

        if ($day == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day.missing'), 'error', 404);
        }

        $actionCount = $this->actionRepository->getActionCount($day_id);
        if (!$this->actionRepository->create($_POST['action_title'], $_POST['action_content'], $actionCount, $day_id, $_POST['action_type'])) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip-add-action.error'), 'error', 500);
        }

        $action = $this->actionRepository->getLastInsertAction();
        $html = $this->responseFactory->html('layouts/_action.html.twig', [
            'action' => $action,
            'day' => $day,
            'trip' => $trip,
            'isOrganiser' => $this->auth->isOrganiser($trip_id)
        ])->createContent();

        return $this->responseFactory->json([
            'message' => $this->lang->get('alerts.trip-add-action.success'),
            'type' => 'success',
            'html' => $html,
            'day_id' => $day_id
        ], 200);
    }
}