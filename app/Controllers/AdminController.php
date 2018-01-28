<?php

namespace App\Controllers;

use App\Repositories\ActionRepository;
use App\Repositories\DayRepository;
use App\Repositories\TripCommentsRepository;
use App\Repositories\TripFilesRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserRepository;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;

class AdminController extends Controller
{
    /** @var UserRepository */
    private $userRepository;

    /** @var TripRepository */
    private $tripRepository;

    /** @var DayRepository */
    private $dayRepository;

    /** @var ActionRepository */
    private $actionRepository;

    /** @var TripCommentsRepository */
    private $tripCommentsRepository;

    /** @var TripFilesRepository */
    private $tripFilesRepository;

    /**
     * AdminController constructor.
     * @param UserRepository $userRepository
     * @param TripRepository $tripRepository
     * @param DayRepository $dayRepository
     * @param ActionRepository $actionRepository
     * @param TripCommentsRepository $tripCommentsRepository
     * @param TripFilesRepository $tripFilesRepository
     */
    function __construct(
            UserRepository $userRepository,
            TripRepository $tripRepository,
            DayRepository $dayRepository,
            ActionRepository $actionRepository,
            TripCommentsRepository $tripCommentsRepository,
            TripFilesRepository $tripFilesRepository) {
        $this->userRepository = $userRepository;
        $this->tripRepository = $tripRepository;
        $this->dayRepository = $dayRepository;
        $this->actionRepository = $actionRepository;
        $this->tripCommentsRepository = $tripCommentsRepository;
        $this->tripFilesRepository = $tripFilesRepository;
    }

    /**
     * @return HtmlResponse
     */
    public function index() {
        $userCount = $this->userRepository->getNewCount();
        $tripCount = $this->tripRepository->getNewCount();
        $dayCount  = $this->dayRepository ->getNewCount();
        $actionCount  = $this->actionRepository->getNewCount();
        $commentCount = $this->tripCommentsRepository->getNewCount();
        $filesCount   = $this->tripFilesRepository->getNewCount();

        $data = [
            'new' => [
                'users' => $userCount,
                'trips' => $tripCount,
                'days'  => $dayCount,
                'actions'  => $actionCount,
                'comments' => $commentCount,
                'files'    => $filesCount
            ]
        ];
        return $this->responseFactory->html('admin/index.html.twig', $data);
    }

    /**
     * @return HtmlResponse
     */
    public function usersPage() {
        $users = $this->userRepository->getNewUsers(10);
        return $this->responseFactory->html('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @return HtmlResponse
     */
    public function tripsPage() {
        $trips = $this->tripRepository->getNewTrips(10);
        return $this->responseFactory->html('admin/trips.html.twig', ['trips' => $trips]);
    }
}