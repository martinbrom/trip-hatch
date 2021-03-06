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

    /** @var TripValidatorFactory */
    private $tripValidatorFactory;

    /**
     * DayController constructor.
     * @param Auth $auth
     * @param DayRepository $dayRepository
     * @param TripRepository $tripRepository
     * @param Language $lang
     * @param ActionRepository $actionRepository
     * @param TripValidatorFactory $tripValidatorFactory
     */
    public function __construct(
            Auth $auth,
            DayRepository $dayRepository,
            TripRepository $tripRepository,
            Language $lang,
            ActionRepository $actionRepository,
            TripValidatorFactory $tripValidatorFactory) {
        $this->auth = $auth;
        $this->dayRepository = $dayRepository;
        $this->tripRepository = $tripRepository;
        $this->lang = $lang;
        $this->actionRepository = $actionRepository;
        $this->tripValidatorFactory = $tripValidatorFactory;
    }

    /**
     * @param Request $request
     * @return JsonResponse|null
     */
    public function delete(Request $request) {
        $trip_id   = $request->getParameter('trip_id');
        $day_id    = $request->getParameter('day_id');

        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateDay($trip_id, $day_id);
        if ($result != NULL) return $result;

        if (!$this->dayRepository->delete($day_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day-delete.error'), 'error', 500);
        }

        $data = [
            'message' => $this->lang->get('alerts.day-delete.success', [$tripValidator->getDay()['title']]),
            'type' => 'success',
            'day_id' => $day_id
        ];
        return $this->responseFactory->json($data, 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function editModal(Request $request) {
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

        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateDay($trip_id, $day_id);
        if ($result != NULL) return $result;

        // TODO: File upload
        $image_id = 2;
        $title = $request->getInput('day_title');
        if (!$this->dayRepository->edit($day_id, $title, $image_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.day-edit.error'), 'danger', 500);
        }

        $day = $this->dayRepository->getDay($day_id);
        $html = $this->responseFactory->html('layouts/_day.html.twig', [
            'day' => $day, 'trip' => $tripValidator->getTrip(),
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
     * @param Request $request
     * @return JsonResponse
     */
    public function addAction(Request $request) {
        $trip_id   = $request->getParameter('trip_id');
        $day_id    = $request->getParameter('day_id');

        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateDay($trip_id, $day_id);
        if ($result != NULL) return $result;

        $actionCount    = $this->actionRepository->getActionCount($day_id);
        $title   = $request->getInput('action_title');
        $content = $request->getInput('action_content');
        $type    = $request->getInput('action_type');
        if (!$this->actionRepository->create($title, $content, $actionCount, $day_id, $type)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip-add-action.error'), 'error', 500);
        }

        $action = $this->actionRepository->getLastInsertAction();
        $html = $this->responseFactory->html('layouts/_action.html.twig', [
            'action' => $action,
            'day' => $tripValidator->getDay(),
            'trip' => $tripValidator->getTrip(),
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