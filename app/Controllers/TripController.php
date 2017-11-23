<?php

namespace App\Controllers;

use App\Repositories\DayRepository;
use App\Repositories\TripRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
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

    /**
     * Creates new instance and injects trip repository, session and response factory
     * @param TripRepository $tripRepository
     * @param Session $session
     * @param AlertHelper $alertHelper
     * @param DayRepository $dayRepository
     * @param Language $lang
     * @param Auth $auth
     */
    function __construct(
            TripRepository $tripRepository,
            Session $session,
            AlertHelper $alertHelper,
            DayRepository $dayRepository,
            Language $lang,
            Auth $auth) {
        $this->tripRepository = $tripRepository;
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
    public function create() {
        return $this->responseFactory->html('trip/create.html.twig');
    }

    /**
     * Creates new trip and redirects user to its page
     * @return RedirectResponse Newly created trip page
     */
    public function store() {
        // TODO: Create trip
        $id = 1; // later will be last insert ID from database
        return $this->redirect('trip/' . $id);
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
            $this->alertHelper->error('Trip doesn\'t exist!');
            return $this->redirect('/trips');
        }

        $days = $this->dayRepository->getDays($trip_id);
        return $this->responseFactory->html('trip/show.html.twig', ['days' => $days, 'trip' => $trip]);
    }

    // TODO: Do I really need all of these?
    public function edit() {}
    public function update() {}
    public function destroy() {}

    /**
     * @param $public_url
     * @return Response
     */
    public function showPublic($public_url) {
        $trip = $this->tripRepository->getTripPublic($public_url);

        if ($trip == NULL) {
            $this->alertHelper->error('Trip doesn\'t exist!');
            return $this->redirect('/trips');
        }

        if ($this->auth->isLogged())
            return $this->redirect('/trip/' . $trip['id']);

        $days = $this->dayRepository->getDays($trip['id']);
        return $this->responseFactory->html('trip/show.html.twig', ['days' => $days, 'title' => $trip['title']]);
    }

    public function publish($trip_id) {
        $this->tripRepository->publishTrip($trip_id);
        return $this->responseFactory->json(['message' => $this->lang->get('alerts.publish.success')], 200);
    }

    public function classify($trip_id) {
        $this->tripRepository->classifyTrip($trip_id);
        return $this->responseFactory->json(['message' => $this->lang->get('alerts.classify.success')], 200);
    }
}