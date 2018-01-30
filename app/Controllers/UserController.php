<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\UserSettingsRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Request;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\RedirectResponse;
use Core\Http\Response\Response;
use Core\Language\Language;
use Core\Mail\Mailer;
use Core\Session;

/**
 * Handles creating responses for user related pages
 * such as login, register and profile
 * @package App\Controllers
 * @author Martin Brom
 */
class UserController extends Controller
{
    /** @var UserRepository Instance for getting data from database */
    private $userRepository;

    /** @var Auth Instance used for authenticating user */
    private $auth;

    /** @var AlertHelper */
    private $alertHelper;

    /** @var Language */
    private $lang;

    /** @var Mailer */
    private $mailer;

    /** @var Session */
    private $session;

    /** @var UserSettingsRepository */
    private $userSettingsRepository;

    /**
     * Creates new instance and injects user repository and auth
     * @param UserRepository $userRepository Instance for getting data from database
     * @param Auth $auth Instance for user authentication
     * @param AlertHelper $alertHelper
     * @param Language $lang
     * @param Mailer $mailer
     * @param Session $session
     * @param UserSettingsRepository $userSettingsRepository
     */
    function __construct(
            UserRepository $userRepository,
            Auth $auth,
            AlertHelper $alertHelper,
            Language $lang,
            Mailer $mailer,
            Session $session,
            UserSettingsRepository $userSettingsRepository) {
        $this->userRepository = $userRepository;
        $this->auth = $auth;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
        $this->mailer = $mailer;
        $this->session = $session;
        $this->userSettingsRepository = $userSettingsRepository;
    }

    /**
     * Returns a html response with login and register page content
     * @return Response Login and register page or
     *                  redirect to dashboard if logged in
     */
    public function loginPage() {
        if ($this->auth->isLogged())
            return $this->route('dashboard');

        return $this->responseFactory->html('user/login.html.twig');
    }

    /**
     * Returns a html response with forgotten password page content
     * @return HtmlResponse Forgotten password page
     */
    public function forgottenPasswordPage() {
        return $this->responseFactory->html('user/forgottenPassword.html.twig');
    }

    /**
     * Tries to log user in and redirects him after
     * @param Request $request
     * @return RedirectResponse Dashboard on successful login,
     *                          login page on unsuccessful
     */
    public function login(Request $request) {
        $email    = $request->getInput('login_email');
        $password = $request->getInput('login_password');

        if ($this->auth->login($email, $password)) {
            $this->alertHelper->success($this->lang->get('alerts.login.success'));
            return $this->route('dashboard');
        }

        $this->alertHelper->error($this->lang->get('alerts.login.wrong'));
        return $this->route('login');
    }

    /**
     * Logs user out and redirects him to login page
     * @return RedirectResponse Login page
     */
    public function logout() {
        $this->auth->logout();
        $this->alertHelper->success($this->lang->get('alerts.logout.success'));
        return $this->route('login');
    }

    /**
     * Creates new user, logs him and redirects to dashboard
     * @param Request $request
     * @return RedirectResponse Login page
     */
    public function register(Request $request) {
        $email    = $request->getInput('register_email');
        $password = $request->getInput('register_password');

        if ($this->auth->login($email, $password)) {
            $this->alertHelper->success($this->lang->get('alerts.login.success'));
            return $this->route('dashboard');
        }

        if ($this->auth->register($email, $password)) {
            $this->auth->login($email, $password);
            $this->alertHelper->success($this->lang->get('alerts.register.success'));
            return $this->route('dashboard');
        }

        $this->alertHelper->error($this->lang->get('alerts.register.error'));
        return $this->route('login');
    }

    /**
     * Sends email with instructions to reset password
     * and redirects user back to login page
     * @param Request $request
     * @return RedirectResponse Login page
     */
    public function forgottenPassword(Request $request) {
        $token = token(32);

        if ($this->auth->isLogged())
            return $this->route('dashboard');

        $email = $request->getInput('forgotten_password_email');

        if (!$this->userSettingsRepository->setPasswordResetToken($email, $token)) {
            $this->alertHelper->error($this->lang->get('alerts.forgotten-password.error'));
            return $this->route('login');
        }

        $this->mailer->forgottenPassword($email, $token);
        $this->alertHelper->success($this->lang->get('alerts.forgotten-password.success'));
        return $this->route('login');
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function resetPasswordPage(Request $request) {
        $email = $request->getParameter('email');
        $token = $request->getParameter('token');

        if (!$this->userSettingsRepository->passwordResetExists($email, $token)) {
            $this->alertHelper->error($this->lang->get('alerts.reset-password.error'));
            return $this->route('login');
        }

        return $this->responseFactory->html('user/resetPassword.html.twig', ['email' => $email, 'token' => $token]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function resetPassword(Request $request) {
        $email = $request->getParameter('email');
        $token = $request->getParameter('token');

        if (!$this->userSettingsRepository->passwordResetExists($email, $token)) {
            $this->alertHelper->error($this->lang->get('alerts.reset-password.error'));
            return $this->route('login');
        }

        $hash = bcrypt($request->getInput('reset_password'));

        if (!$this->userSettingsRepository->resetPassword($email, $hash)) {
            $this->alertHelper->error($this->lang->get('alerts.reset-password.error'));
            return $this->route('login');
        }

        $this->alertHelper->success($this->lang->get('alerts.reset-password.success'));
        return $this->route('login');
    }
}