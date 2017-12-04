<?php

namespace Core\Validation;

use App\Repositories\ActionRepository;
use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use Core\Factories\ResponseFactory;
use Core\Http\Response\JsonResponse;
use Core\Language\Language;

/**
 * Class TripValidator
 * @package Core\Validation
 * @author Martin Brom
 */
class TripValidator
{
    /** @var TripRepository */
    private $tripRepository;

    /** @var DayRepository */
    private $dayRepository;

    /** @var ActionRepository */
    private $actionRepository;

    /** @var ResponseFactory */
    private $responseFactory;

    /** @var Language */
    private $lang;

    private $trip;
    private $day;
    private $action;

    /**
     * TripValidator constructor.
     * @param TripRepository $tripRepository
     * @param DayRepository $dayRepository
     * @param ActionRepository $actionRepository
     * @param ResponseFactory $responseFactory
     * @param Language $lang
     */
    function __construct(
            TripRepository $tripRepository,
            DayRepository $dayRepository,
            ActionRepository $actionRepository,
            ResponseFactory $responseFactory,
            Language $lang) {
        $this->tripRepository = $tripRepository;
        $this->dayRepository = $dayRepository;
        $this->actionRepository = $actionRepository;
        $this->responseFactory = $responseFactory;
        $this->lang = $lang;
    }

    /**
     * @param int $trip_id
     * @return JsonResponse|null
     */
    public function validateTrip(int $trip_id) {
        $this->trip = $this->tripRepository->getTrip($trip_id);
        return $this->trip == NULL ? $this->responseFactory->jsonAlert($this->lang->get('alerts.trip.missing'), 'error', 404) : NULL;
    }

    /**
     * @param int $trip_id
     * @param int $day_id
     * @return JsonResponse|null
     */
    public function validateDay(int $trip_id, int $day_id) {
        $resultTrip = $this->validateTrip($trip_id);
        if ($resultTrip != NULL) return $resultTrip;

        $this->day = $this->dayRepository->getTripDay($trip_id, $day_id);
        return $this->day == NULL ? $this->responseFactory->jsonAlert($this->lang->get('alerts.day.missing'), 'error', 404) : NULL;
    }

    /**
     * @param int $trip_id
     * @param int $day_id
     * @param int $action_id
     * @return JsonResponse|null
     */
    public function validateAction(int $trip_id, int $day_id, int $action_id) {
        $resultTrip = $this->validateTrip($trip_id);
        if ($resultTrip != NULL) return $resultTrip;

        $resultDay = $this->validateDay($trip_id, $day_id);
        if ($resultDay != NULL) return $resultDay;

        $this->action = $this->actionRepository->getDayAction($day_id, $action_id);
        return $this->action == NULL ? $this->responseFactory->jsonAlert($this->lang->get('alerts.action.missing'), 'error', 404) : NULL;
    }

    /**
     * @return mixed
     */
    public function getTrip() {
        return $this->trip;
    }

    /**
     * @return mixed
     */
    public function getDay() {
        return $this->day;
    }

    /**
     * @return mixed
     */
    public function getAction() {
        return $this->action;
    }
}