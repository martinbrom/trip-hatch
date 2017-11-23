<?php

namespace App\Controllers;

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

    /**
     * AdminController constructor.
     * @param UserRepository $userRepository
     * @param TripRepository $tripRepository
     */
    function __construct(UserRepository $userRepository, TripRepository $tripRepository) {
        $this->userRepository = $userRepository;
        $this->tripRepository = $tripRepository;
    }

    /**
     * @return HtmlResponse
     */
    public function index() {
        return $this->responseFactory->html('admin/index.html.twig');
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