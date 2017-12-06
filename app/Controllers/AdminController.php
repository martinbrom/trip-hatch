<?php

namespace App\Controllers;

use App\Repositories\DayRepository;
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

    /**
     * AdminController constructor.
     * @param UserRepository $userRepository
     * @param TripRepository $tripRepository
     * @param DayRepository $dayRepository
     */
    function __construct(UserRepository $userRepository, TripRepository $tripRepository, DayRepository $dayRepository) {
        $this->userRepository = $userRepository;
        $this->tripRepository = $tripRepository;
        $this->dayRepository = $dayRepository;
    }

    /**
     * @return HtmlResponse
     */
    public function index() {
        $userCount = $this->userRepository->getNewCount();
        $tripCount = $this->tripRepository->getNewCount();
        $dayCount  = $this->dayRepository ->getNewCount();

        $data = [
            'new' => [
                'users' => $userCount,
                'trips' => $tripCount,
                'days'  => $dayCount
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