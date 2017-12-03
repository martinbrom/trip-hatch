<?php

namespace App\Controllers;

use App\Repositories\ActionRepository;
use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Response\JsonResponse;
use Core\Language\Language;

/**
 * Class ActionController
 * @package App\Controllers
 * @author Martin Brom
 */
class ActionController extends Controller
{
    /** @var ActionRepository */
    private $actionRepository;

    /** @var DayRepository */
    private $dayRepository;

    /** @var TripRepository */
    private $tripRepository;

    /** @var Language */
    private $lang;

    /** @var Auth */
    private $auth;

    /**
     * ActionController constructor.
     * @param ActionRepository $actionRepository
     * @param DayRepository $dayRepository
     * @param TripRepository $tripRepository
     * @param Language $lang
     * @param Auth $auth
     */
    function __construct(
            ActionRepository $actionRepository,
            DayRepository $dayRepository,
            TripRepository $tripRepository,
            Language $lang,
            Auth $auth) {
        $this->actionRepository = $actionRepository;
        $this->dayRepository = $dayRepository;
        $this->tripRepository = $tripRepository;
        $this->lang = $lang;
        $this->auth = $auth;
    }

    /**
     * Returns a response with all actions for given day or alert on error
     * @param int $trip_id ID of a trip
     * @param int $day_id ID of a day
     * @return JsonResponse All actions for given day or alert
     */
    public function actions($trip_id, $day_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip.missing'), 'error', 404);
        }

        $day = $this->dayRepository->getDay($day_id);

        if ($day == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day.missing'), 'error', 404);
        }

        $actions = $this->actionRepository->getActions($day_id);
        $html = $this->responseFactory->html('trip/actions.html.twig', [
            'trip' => $trip,
            'day' => $day,
            'actions' => $actions,
            'isOrganiser' => $this->auth->isOrganiser($trip_id)
        ])->createContent();

        return $this->responseFactory->json([
            'message' => $this->lang->get('alerts.actions.success'),
            'type' => 'success',
            'html' => $html
        ]);
    }

    /**
     * @param $trip_id
     * @param $day_id
     * @return JsonResponse
     */
    public function addActionModal($trip_id, $day_id) {
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
}