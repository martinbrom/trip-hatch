<?php

namespace App\Controllers;

use App\Repositories\ActionTypeRepository;
use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserTripRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Factories\TripValidatorFactory;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Session;

/**
 * Handles creating responses for trip related pages
 * @package App\Controllers
 * @author Martin Brom
 */
class TripController extends Controller
{
    /** @var TripRepository Instance for getting data from database */
    private $tripRepository;

    /** @var Session Instance for getting data from session */
    private $session;

    /** @var AlertHelper Instance for creating alerts */
    private $alertHelper;
    
    /** @var DayRepository */
    private $dayRepository;

    /** @var Language */
    private $lang;

    /** @var Auth */
    private $auth;

    /** @var UserTripRepository */
    private $userTripRepository;

    /** @var ActionTypeRepository */
    private $actionTypeRepository;

    /** @var TripValidatorFactory */
    private $tripValidatorFactory;

    /**
     * TripController constructor.
     * @param TripRepository $tripRepository
     * @param UserTripRepository $userTripRepository
     * @param Session $session
     * @param AlertHelper $alertHelper
     * @param DayRepository $dayRepository
     * @param Language $lang
     * @param Auth $auth
     * @param ActionTypeRepository $actionTypeRepository
     * @param TripValidatorFactory $tripValidatorFactory
     */
    function __construct(
            TripRepository $tripRepository,
            UserTripRepository $userTripRepository,
            Session $session,
            AlertHelper $alertHelper,
            DayRepository $dayRepository,
            Language $lang,
            Auth $auth,
            ActionTypeRepository $actionTypeRepository,
            TripValidatorFactory $tripValidatorFactory) {
        $this->tripRepository = $tripRepository;
        $this->userTripRepository = $userTripRepository;
        $this->session = $session;
        $this->alertHelper = $alertHelper;
        $this->dayRepository = $dayRepository;
        $this->lang = $lang;
        $this->auth = $auth;
        $this->actionTypeRepository = $actionTypeRepository;
        $this->tripValidatorFactory = $tripValidatorFactory;
    }

    /**
     * Returns a html response with dashboard content and all trips for logged user
     * @return HtmlResponse Dashboard page
     */
    public function index() {
        $trips = $this->tripRepository->getTrips($this->session->get('user.id'));
        return $this->responseFactory->html('trip/index.html.twig', ['trips' => $trips]);
    }

    /**
     * Returns a html response with trip creating page content
     * @return HtmlResponse New trip page
     */
    public function createPage() {
        return $this->responseFactory->html('trip/create.html.twig');
    }

    /**
     * Creates new trip and redirects user to its page
     * @param Request $request
     * @return Response Newly created trip page
     */
    public function create(Request $request) {
        $title = $request->getInput('trip_title');

        if (!$this->tripRepository->createTrip($title)) {
            $this->alertHelper->error($this->lang->get('alerts.trip-create.error'));
            return $this->route('dashboard');
        }

        $trip_id = $this->tripRepository->lastInsertID();
        if (!$this->userTripRepository->setTripOwner($this->session->get('user.id'), $trip_id)) {
            $this->alertHelper->error($this->lang->get('alerts.set-owner.error'));
            return $this->route('dashboard');
        }

        $this->alertHelper->success($this->lang->get('alerts.trip-create.success'));
        return $this->tripRoute('show', $trip_id);
    }

    /**
     * Returns a html with with a content of trip with a given id
     * or redirects to trip list page if a trip with given id doesn't exist
     * @param Request $request
     * @return HtmlResponse|RedirectResponse Content of trip page or redirect
     *                                       to all trips if trip doesn't exist
     */
    public function show(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $trip = $this->tripRepository->getTrip($trip_id);

        $days = $this->dayRepository->getDays($trip_id);
        if (empty($days))
            $this->alertHelper->info($this->lang->get('alerts.trip.no-days'));

        $action_types = $this->actionTypeRepository->getAll();

        return $this->responseFactory->html('trip/show.html.twig', [
            'days' => $days,
            'trip' => $trip,
            'action_types' => $action_types,
            'isTraveller' => true
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request) {
        $trip_id = $request->getParameter('trip_id');

        $title = $request->getInput('trip_title');
        $start_date = $request->getInput('trip_start_date');

        $image_id = 2;
        if (!$this->tripRepository->edit($trip_id, $title, $start_date, $image_id)) {
            $this->alertHelper->error($this->lang->get('alerts.trip-edit.error'));
            return $this->route('dashboard');
        }

        $this->alertHelper->success($this->lang->get('alerts.trip-edit.success'));
        return $this->route('trip.show', ['trip_id' => $trip_id]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function editPage(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $trip = $this->tripRepository->getTrip($trip_id);

        $date = date('Y-m-d', strtotime($trip['start_date']));

        return $this->responseFactory->html('trip/edit.html.twig', ['trip' => $trip, 'date' => $date]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function managePeoplePage(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $trip = $this->tripRepository->getTrip($trip_id);

        $travellers = $this->userTripRepository->getTravellers($trip_id);
        if (empty($travellers))
            $this->alertHelper->warning($this->lang->get('alerts.trip.no-travellers'));

        return $this->responseFactory->html('trip/managePeople.html.twig', ['trip' => $trip, 'travellers' => $travellers]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function manageStaffPage(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $trip = $this->tripRepository->getTrip($trip_id);

        $organisers = $this->userTripRepository->getOrganisers($trip_id);

        if (empty($organisers))
            $this->alertHelper->warning($this->lang->get('alerts.trip.no-organisers'));

        return $this->responseFactory->html('trip/manageStaff.html.twig', ['trip' => $trip, 'organisers' => $organisers]);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function showPublic(Request $request) {
        $public_url = $request->getParameter('public_url');
        $trip = $this->tripRepository->getTripPublic($public_url);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        $trip_id = $trip['id'];

        $isTraveller = $this->auth->isTraveller($trip_id);

        if ($isTraveller) {
            return $this->tripRoute('show', $trip_id);
        }

        $days = $this->dayRepository->getDays($trip_id);
        return $this->responseFactory->html('trip/show.html.twig', ['days' => $days, 'trip' => $trip, 'isTraveller' => $isTraveller]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function publish(Request $request) {
        $trip_id = $request->getParameter('trip_id');

        if (!$this->tripRepository->publishTrip($trip_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.publish.error'), 'error', 500);
        }

        return $this->responseFactory->jsonAlert($this->lang->get('alerts.publish.success'), 'success', 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function classify(Request $request) {
        $trip_id = $request->getParameter('trip_id');

        if (!$this->tripRepository->classifyTrip($trip_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.classify.error'), 'error', 500);
        }

        return $this->responseFactory->jsonAlert($this->lang->get('alerts.classify.success'), 'success', 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeUser(Request $request) {
        $user_trip_id = $request->getInput('user_trip_id');

        if (!$this->userTripRepository->isExactlyTraveller($user_trip_id))
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.remove-user.wrong-role'), 'danger', 401);

        if ($this->userTripRepository->removeTraveller($user_trip_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.remove-user.success'), 'success', 200);
        }

        return $this->responseFactory->jsonAlert($this->lang->get('alerts.remove-user.error'), 'danger', 500);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function promoteUser(Request $request) {
        $user_trip_id = $request->getInput('user_trip_id');

        if (!$this->userTripRepository->isExactlyTraveller($user_trip_id))
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.promote-user.wrong-role'), 'danger', 401);

        if ($this->userTripRepository->setOrganiser($user_trip_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.promote-user.success'), 'success', 200);
        }

        return $this->responseFactory->jsonAlert($this->lang->get('alerts.promote-user.error'), 'danger', 500);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function demoteUser(Request $request) {
        $user_trip_id = $request->getInput('user_trip_id');

        if (!$this->userTripRepository->isExactlyOrganiser($user_trip_id))
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.demote-user.wrong-role'), 'danger', 401);

        if ($this->userTripRepository->setTraveller($user_trip_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.demote-user.success'), 'success', 200);
        }

        return $this->responseFactory->jsonAlert($this->lang->get('alerts.demote-user.error'), 'danger', 500);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        if (!$this->tripRepository->delete($trip_id)) {
            $this->alertHelper->error($this->lang->get('alerts.trip-delete.error'));
            return $this->route('trip.show', ['trip_id' => $trip_id]);
        }

        $this->alertHelper->success($this->lang->get('alerts.trip-delete.success'));
        return $this->route('dashboard');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function addDay(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $tripValidator = $this->tripValidatorFactory->make();
        $result = $tripValidator->validateTrip($trip_id);
        if ($result != NULL) return $result;

        $dayCount = $this->dayRepository->getDayCount($trip_id);
        if (!$this->dayRepository->create($trip_id, $dayCount)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip-add-day.error'), 'error', 500);
        }

        $day = $this->dayRepository->getLastInsertDay();
        $html = $this->responseFactory->html('layouts/_day.html.twig', [
            'day' => $day, 'trip' => $tripValidator->getTrip(),
            'isOrganiser' => $this->auth->isOrganiser($trip_id)
        ])->createContent();

        $data = ['message' => $this->lang->get('alerts.trip-add-day.success'), 'type' => 'success', 'html' => $html];
        return $this->responseFactory->json($data, 200);
    }
}