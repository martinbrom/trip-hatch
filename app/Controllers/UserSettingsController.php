<?php

namespace App\Controllers;

use App\Repositories\UserSettingsRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Session;

class UserSettingsController extends Controller
{
    /** @var AlertHelper */
    private $alertHelper;

    /** @var Language */
    private $lang;

    /** @var UserSettingsRepository */
    private $userSettingsRepository;

    /** @var Session */
    private $session;

    /** @var Auth */
    private $auth;

    /**
     * UserSettingsController constructor.
     * @param AlertHelper $alertHelper
     * @param Language $lang
     * @param UserSettingsRepository $userSettingsRepository
     * @param Session $session
     * @param Auth $auth
     */
    function __construct(
            AlertHelper $alertHelper,
            Language $lang,
            UserSettingsRepository $userSettingsRepository,
            Session $session,
            Auth $auth) {
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
        $this->userSettingsRepository = $userSettingsRepository;
        $this->session = $session;
        $this->auth = $auth;
    }

    /**
     * Returns a html response with profile page content
     * @return HtmlResponse Profile page
     */
    public function profile() {
        return $this->responseFactory->html('settings/profile.html.twig');
    }

    /**
     * @return HtmlResponse
     */
    public function changeDisplayNamePage() {
        return $this->responseFactory->html('settings/changeDisplayName.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changeDisplayName(Request $request) {
        $display_name = $request->getInput('display_name');
        if ($display_name == "")
            $display_name = NULL;

        if (!$this->userSettingsRepository->changeDisplayName($this->session->get('user.id'), $display_name)) {
            $this->alertHelper->success($this->lang->get('alerts.change-display-name.error'));
            return $this->error(500);
        }

        $this->alertHelper->success($this->lang->get('alerts.change-display-name.success'));
        return $this->route('profile');
    }

    /**
     * @return HtmlResponse
     */
    public function changePasswordPage() {
        return $this->responseFactory->html('settings/changePassword.html.twig');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function changePassword(Request $request) {
        $password = $request->getInput('new_password');

        $hash = bcrypt($password);
        if (!$this->userSettingsRepository->changePassword($this->session->get('user.id'), $hash)) {
            $this->alertHelper->error($this->lang->get('alerts.change-password.error'));
            return $this->error(500);
        }

        $this->auth->login($this->session->get('user.email'), $hash);
        $this->alertHelper->success($this->lang->get('alerts.change-password.success'));
        return $this->route('profile');
    }
}