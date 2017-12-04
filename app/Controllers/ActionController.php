<?php

namespace App\Controllers;

use App\Repositories\ActionRepository;
use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use Core\Auth;
use Core\Factories\TripValidatorFactory;
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

    /** @var TripValidatorFactory */
    private $tripValidatorFactory;

    /**
     * ActionController constructor.
     * @param ActionRepository $actionRepository
     * @param DayRepository $dayRepository
     * @param TripRepository $tripRepository
     * @param Language $lang
     * @param Auth $auth
     * @param TripValidatorFactory $tripValidatorFactory
     */
    function __construct(
            ActionRepository $actionRepository,
            DayRepository $dayRepository,
            TripRepository $tripRepository,
            Language $lang,
            Auth $auth,
            TripValidatorFactory $tripValidatorFactory) {
        $this->actionRepository = $actionRepository;
        $this->dayRepository = $dayRepository;
        $this->tripRepository = $tripRepository;
        $this->lang = $lang;
        $this->auth = $auth;
        $this->tripValidatorFactory = $tripValidatorFactory;
    }

    /**
     * Returns a response with all actions for given day or alert on error
     * @param int $trip_id ID of a trip
     * @param int $day_id ID of a day
     * @return JsonResponse All actions for given day or alert
     */
    public function actions($trip_id, $day_id) {
        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateDay($trip_id, $day_id);
        if ($result != NULL) return $result;

        $actions = $this->actionRepository->getActions($day_id);
        $html = $this->responseFactory->html('trip/actions.html.twig', [
            'trip' => $tripValidator->getTrip(),
            'day' => $tripValidator->getDay(),
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
        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateDay($trip_id, $day_id);
        if ($result != NULL) return $result;

        return $this->responseFactory->json(['day' => $tripValidator->getDay(), 'trip' => $tripValidator->getTrip()], 200);
    }

    /**
     * @param $trip_id
     * @param $day_id
     * @param $action_id
     * @return JsonResponse
     */
    public function edit($trip_id, $day_id, $action_id) {
        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateAction($trip_id, $day_id, $action_id);
        if ($result != NULL) return $result;

        if (!$this->actionRepository->edit(
                $action_id,
                $_POST['action_edit_title'],
                $_POST['action_edit_content'],
                $_POST['action_edit_type'])) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip-edit-action.error'), 'error', 500);
        }

        $html = $this->responseFactory->html('layouts/_action.html.twig', [
            'action' => $this->actionRepository->getAction($action_id),
            'day' => $tripValidator->getDay(),
            'trip' => $tripValidator->getTrip(),
            'isOrganiser' => $this->auth->isOrganiser($trip_id)
        ])->createContent();

        return $this->responseFactory->json([
            'message' => $this->lang->get('alerts.trip-edit-action.success'),
            'type' => 'success',
            'html' => $html,
            'action_id' => $action_id,
            'day_id' => $day_id
        ], 200);
    }

    /**
     * @param $trip_id
     * @param $day_id
     * @param $action_id
     * @return JsonResponse
     */
    public function editModal($trip_id, $day_id, $action_id) {
        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateAction($trip_id, $day_id, $action_id);
        if ($result != NULL) return $result;

        return $this->responseFactory->json([
            'message' => 'Success',
            'type' => 'success',
            'action' => $tripValidator->getAction()
        ], 200);
    }

    /**
     * @param $trip_id
     * @param $day_id
     * @param $action_id
     * @return JsonResponse
     */
    public function delete($trip_id, $day_id, $action_id) {
        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateAction($trip_id, $day_id, $action_id);
        if ($result != NULL) return $result;

        if (!$this->actionRepository->delete($action_id)) {
            return $this->responseFactory->jsonAlert('Failure', 'error', 500);
        }

        return $this->responseFactory->json([
            'message' => 'Success',
            'type' => 'success',
            'action_id' => $action_id
        ], 200);
    }
}