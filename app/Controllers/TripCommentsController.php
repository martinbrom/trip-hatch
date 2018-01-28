<?php

namespace App\Controllers;

use App\Repositories\TripCommentsRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserTripRepository;
use Core\AlertHelper;
use Core\Http\Controller;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Session;
use phpDocumentor\Reflection\Types\Null_;

/**
 * Handles creating responses for pages related to the main page
 * such as frequently asked questions or terms and conditions
 * @package App\Controllers
 * @author Martin Brom
 */
class TripCommentsController extends Controller
{
    /** @var TripRepository */
    private $tripRepository;

    /** @var AlertHelper */
    private $alertHelper;

    /** @var Language */
    private $lang;

    /** @var TripCommentsRepository */
    private $tripCommentsRepository;

    /** @var UserTripRepository */
    private $userTripRepository;

    /** @var Session */
    private $session;

    /**
     * TripCommentsController constructor.
     * @param TripRepository $tripRepository
     * @param AlertHelper $alertHelper
     * @param Language $lang
     * @param TripCommentsRepository $tripCommentsRepository
     * @param UserTripRepository $userTripRepository
     * @param Session $session
     */
    function __construct(
            TripRepository $tripRepository,
            AlertHelper $alertHelper,
            Language $lang,
            TripCommentsRepository $tripCommentsRepository,
            UserTripRepository $userTripRepository,
            Session $session) {
        $this->tripRepository = $tripRepository;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
        $this->tripCommentsRepository = $tripCommentsRepository;
        $this->userTripRepository = $userTripRepository;
        $this->session = $session;
    }

    /**
     * @param $trip_id
     * @return Response
     */
    public function index($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        $comments = $this->tripCommentsRepository->getComments($trip_id);

        return $this->responseFactory->html('trip/comments.html.twig', ['trip' => $trip, 'comments' => $comments]);
    }

    /**
     * @param $trip_id
     * @return Response
     */
    public function create($trip_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        $user_trip_id = $this->userTripRepository->getID($this->session->get('user.id'), $trip_id);

        if ($user_trip_id == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip-comment.traveller'));
            return $this->route('dashboard');
        }

        if (!$this->tripCommentsRepository->create($user_trip_id['id'], $_POST['comment_content'])) {
            $this->alertHelper->error($this->lang->get('alerts.trip-comment.error'));
            return $this->route('dashboard');
        }

        $this->alertHelper->success($this->lang->get('alerts.trip-comment.success'));
        return $this->route('trip.comments', ['trip_id' => $trip_id]);
    }

    /**
     * @param $trip_id
     * @param $comment_id
     * @return Response
     */
    public function delete($trip_id, $comment_id) {
        $trip = $this->tripRepository->getTrip($trip_id);

        if ($trip == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip.missing'));
            return $this->route('dashboard');
        }

        $comment = $this->tripCommentsRepository->getComment($comment_id);

        if ($comment == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip-comment.missing'));
            return $this->route('trip.comments', ['trip_id' => $trip_id]);
        }

        if (!$this->tripCommentsRepository->delete($comment_id)) {
            $this->alertHelper->error($this->lang->get('alerts.comment-delete.error'));
        }

        $this->alertHelper->success($this->lang->get('alerts.comment-delete.success'));
        return $this->route('trip.comments', ['trip_id' => $trip_id]);
    }
}