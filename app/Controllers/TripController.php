<?php

namespace App\Controllers;

use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserTripRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Http\Controller;
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

    /**
     * Creates new instance and injects trip repository, session and response factory
     * @param TripRepository $tripRepository
     * @param UserTripRepository $userTripRepository
     * @param Session $session
     * @param AlertHelper $alertHelper
     * @param DayRepository $dayRepository
     * @param Language $lang
     * @param Auth $auth
     */
    function __construct(
            TripRepository $tripRepository,
            UserTripRepository $userTripRepository,
            Session $session,
            AlertHelper $alertHelper,
            DayRepository $dayRepository,
            Language $lang,
            Auth $auth) {
        $this->tripRepository = $tripRepository;
        $this->userTripRepository = $userTripRepository;
        $this->session = $session;
        $this->alertHelper = $alertHelper;
        $this->dayRepository = $dayRepository;
        $this->lang = $lang;
        $this->auth = $auth;
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
     * @return Response Newly created trip page
     */
    public function create() {
        if (!$this->tripRepository->createTrip($_POST['trip_title'])) {
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
     * @param int $trip_id ID of a trip
     * @return HtmlResponse|RedirectResponse Content of trip page or redirect
     *                                       to all trips if trip doesn't exist
     */
    public function show($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        $days = $this->dayRepository->getDays($trip_id);
        if (empty($days))
            $this->alertHelper->info($this->lang->get('alerts.trip.no-days'));

        return $this->responseFactory->html('trip/show.html.twig', ['days' => $days, 'trip' => $trip]);
    }

    public function edit($trip_id) {}

    /**
     * @param $trip_id
     * @return Response
     */
    public function editPage($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == null) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        return $this->responseFactory->html('trip/edit.html.twig', ['trip' => $trip]);
    }

    /**
     * @param $trip_id
     * @return Response
     */
    public function managePeoplePage($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        $travellers = $this->userTripRepository->getTravellers($trip['id']);
        if (empty($travellers))
            $this->alertHelper->warning($this->lang->get('alerts.trip.no-travellers'));

        return $this->responseFactory->html('trip/managePeople.html.twig', ['trip' => $trip, 'travellers' => $travellers]);
    }

    /**
     * @param $trip_id
     * @return Response
     */
    public function manageStaffPage($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        return $this->responseFactory->html('trip/manageStaff.html.twig', ['trip' => $trip]);
    }

    /**
     * @param $public_url
     * @return Response
     */
    public function showPublic($public_url) {
        $trip = $this->tripRepository->getTripPublic($public_url);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        if ($this->auth->isLogged())
            return $this->tripRoute('show', $trip['id']);

        $days = $this->dayRepository->getDays($trip['id']);
        return $this->responseFactory->html('trip/show.html.twig', ['days' => $days, 'trip' => $trip]);
    }

    /**
     * @param $trip_id
     * @return JsonResponse
     */
    public function publish($trip_id) {
        if ($this->tripRepository->publishTrip($trip_id))
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.publish.success'), 'success', 200);

        return $this->responseFactory->jsonAlert($this->lang->get('alerts.publish.error'), 'error', 500);
    }

    /**
     * @param $trip_id
     * @return JsonResponse
     */
    public function classify($trip_id) {
        if ($this->tripRepository->classifyTrip($trip_id))
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.classify.success'), 'success', 200);

        return $this->responseFactory->jsonAlert($this->lang->get('alerts.classify.error'), 'error', 500);
    }

    /**
     * @param $user_trip_id
     * @return JsonResponse
     */
    public function removeUser($trip_id, $user_trip_id) {
        // TODO: Deleting when user_trip has comments...
        if (!$this->userTripRepository->isExactlyTraveller($user_trip_id))
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.remove-user.wrong-role'), 'danger', 401);

        if ($this->userTripRepository->removeTraveller($user_trip_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.remove-user.success'), 'success', 200);
        }

        return $this->responseFactory->jsonAlert($this->lang->get('alerts.remove-user.error'),'danger', 500);
    }

    public function promoteUser($trip_id, $user_trip_id) {

    }

    /**
     * @param $trip_id
     * @return Response
     */
    public function invitePage($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        return $this->responseFactory->html('trip/invite.html.twig', ['trip' => $trip]);
    }

    public function invite($trip_id) {
        // TODO: Send a link with unique invitation token
    }
}