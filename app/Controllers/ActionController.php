<?php

namespace App\Controllers;

use App\Repositories\ActionRepository;
use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use Core\Auth;
use Core\Factories\TripValidatorFactory;
use Core\Http\Controller;
use Core\Http\Request;
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
     * @param Request $request
     * @return JsonResponse All actions for given day or alert
     */
    public function actions(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $day_id =  $request->getParameter('day_id');

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
     * @param Request $request
     * @return JsonResponse
     */
    public function addActionModal(Request $request) {
        $trip_id   = $request->getParameter('trip_id');
        $day_id    = $request->getParameter('day_id');

        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateDay($trip_id, $day_id);
        if ($result != NULL) return $result;

        return $this->responseFactory->json(['day' => $tripValidator->getDay(), 'trip' => $tripValidator->getTrip()], 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(Request $request) {
        $trip_id   = $request->getParameter('trip_id');
        $day_id    = $request->getParameter('day_id');
        $action_id = $request->getParameter('action_id');

        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateAction($trip_id, $day_id, $action_id);
        if ($result != NULL) return $result;

        $title   = $request->getInput('action_edit_title');
        $content = $request->getInput('action_edit_content');
        $type    = $request->getInput('action_edit_type');

        if (!$this->actionRepository->edit($action_id, $title, $content, $type)) {
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
     * @param Request $request
     * @return JsonResponse
     */
    public function editModal(Request $request) {
        $trip_id   = $request->getParameter('trip_id');
        $day_id    = $request->getParameter('day_id');
        $action_id = $request->getParameter('action_id');

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
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request) {
        $trip_id   = $request->getParameter('trip_id');
        $day_id    = $request->getParameter('day_id');
        $action_id = $request->getParameter('action_id');

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