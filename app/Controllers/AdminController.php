<?php

namespace App\Controllers;

use App\Repositories\ActionRepository;
use App\Repositories\DayRepository;
use App\Repositories\TripCommentsRepository;
use App\Repositories\TripFilesRepository;
use App\Repositories\TripRepository;
use App\Repositories\UserRepository;
use Core\AlertHelper;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\RedirectResponse;
use Core\Language\Language;
use Core\Session;

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

    /** @var AlertHelper */
    private $alertHelper;

    /** @var Session */
    private $session;

    /** @var Language */
    private $lang;

    /**
     * AdminController constructor.
     * @param UserRepository $userRepository
     * @param TripRepository $tripRepository
     * @param DayRepository $dayRepository
     * @param ActionRepository $actionRepository
     * @param TripCommentsRepository $tripCommentsRepository
     * @param TripFilesRepository $tripFilesRepository
     * @param AlertHelper $alertHelper
     * @param Session $session
     * @param Language $lang
     */
    function __construct(
            UserRepository $userRepository,
            TripRepository $tripRepository,
            DayRepository $dayRepository,
            ActionRepository $actionRepository,
            TripCommentsRepository $tripCommentsRepository,
            TripFilesRepository $tripFilesRepository,
            AlertHelper $alertHelper,
            Session $session,
            Language $lang) {
        $this->userRepository = $userRepository;
        $this->tripRepository = $tripRepository;
        $this->dayRepository = $dayRepository;
        $this->actionRepository = $actionRepository;
        $this->tripCommentsRepository = $tripCommentsRepository;
        $this->tripFilesRepository = $tripFilesRepository;
        $this->alertHelper = $alertHelper;
        $this->session = $session;
        $this->lang = $lang;
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
        $users = $this->userRepository->getUsers();
        return $this->responseFactory->html('admin/users.html.twig', ['users' => $users]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteUser(Request $request) {
        $user_id = $request->getParameter('user_id');

        $user = $this->userRepository->getUserByID($user_id);
        $self_id = $this->session->get('user.id');

        if ($self_id == $user_id) {
            $this->alertHelper->error($this->lang->get('alerts.admin.delete-user.self'));
            return $this->route('admin.users');
        }

        if ($user == NULL) {
            $this->alertHelper->error($this->lang->get('alerts.user.missing'));
            return $this->route('admin.users');
        }

        if ($user['is_admin'] == 1) {
            $this->alertHelper->error($this->lang->get('alerts.admin.delete-user.admin'));
            return $this->route('admin.users');
        }

        if (!$this->userRepository->delete($user_id)) {
            $this->alertHelper->error($this->lang->get('alerts.admin.delete-user.error'));
            return $this->route('admin.users');
        }

        $this->alertHelper->success($this->lang->get('alerts.admin.delete-user.success'));
        return $this->route('admin.users');
    }
}