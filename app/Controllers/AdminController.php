<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Core\Http\Controller;
use Core\Http\Response\Response;

class AdminController extends Controller
{
    /** @var UserRepository */
    private $userRepository;

    /**
     * AdminController constructor.
     * @param UserRepository $userRepository
     */
    function __construct(UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    /**
     * @return Response
     */
    public function index() {
        $users = $this->userRepository->getNewUsers(10);
        return $this->responseFactory->html('admin/index.html.twig', ['users' => $users]);
    }
}