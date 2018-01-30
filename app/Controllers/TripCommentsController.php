<?php

namespace App\Controllers;

use App\Repositories\TripCommentsRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserTripRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Session;

/**
 * Class TripCommentsController
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

    /** @var Auth */
    private $auth;

    /**
     * TripCommentsController constructor.
     * @param TripRepository $tripRepository
     * @param AlertHelper $alertHelper
     * @param Language $lang
     * @param TripCommentsRepository $tripCommentsRepository
     * @param UserTripRepository $userTripRepository
     * @param Session $session
     * @param Auth $auth
     */
    function __construct(
            TripRepository $tripRepository,
            AlertHelper $alertHelper,
            Language $lang,
            TripCommentsRepository $tripCommentsRepository,
            UserTripRepository $userTripRepository,
            Session $session,
            Auth $auth) {
        $this->tripRepository = $tripRepository;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
        $this->tripCommentsRepository = $tripCommentsRepository;
        $this->userTripRepository = $userTripRepository;
        $this->session = $session;
        $this->auth = $auth;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) {
        $trip_id = $request->getParameter('trip_id');
        $trip = $this->tripRepository->getTrip($trip_id);

        $comments = $this->tripCommentsRepository->getComments($trip_id);

        return $this->responseFactory->html('trip/comments.html.twig',
            ['trip' => $trip, 'comments' => $comments, 'isOrganiser' => $this->auth->isOrganiser($trip_id)]
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request) {
        $trip_id = $request->getParameter('trip_id');

        $user_trip_id = $this->userTripRepository->getID($this->session->get('user.id'), $trip_id);
        $content = $request->getInput('comment_content');

        if ($user_trip_id == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.trip-comment.traveller'));
            return $this->route('dashboard');
        }

        if (!$this->tripCommentsRepository->create($user_trip_id['id'], $content)) {
            $this->alertHelper->error($this->lang->get('alerts.trip-comment.error'));
            return $this->route('dashboard');
        }

        $this->alertHelper->success($this->lang->get('alerts.trip-comment.success'));
        return $this->tripRoute('comments', $trip_id);
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request) {
        $comment_id = $request->getParameter('comment_id');
        $comment = $this->tripCommentsRepository->getComment($comment_id);

        if ($comment == NULL) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.trip-comment.missing'), 'error', 404);
        }

        if (!$this->tripCommentsRepository->delete($comment_id)) {
            return $this->responseFactory->jsonAlert($this->lang->get('alerts.comment-delete.error'), 'error', 500);
        }

        return $this->responseFactory->json([
            'message' => 'Success',
            'type' => 'success',
            'comment_id' => $comment_id
        ], 200);
    }
}