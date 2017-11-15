<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Factories\ResponseFactory;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
use Core\Language;

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

    /** @var ResponseFactory Instance for creating responses */
    private $responseFactory;

    /** @var AlertHelper */
    private $alertHelper;

    /** @var Language */
    private $lang;

    /**
     * Creates new instance and injects user repository and auth
     * @param UserRepository $userRepository Instance for getting data from database
     * @param Auth $auth Instance for user authentication
     * @param ResponseFactory $responseFactory
     * @param AlertHelper $alertHelper
     * @param Language $lang
     */
    function __construct(UserRepository $userRepository, Auth $auth, ResponseFactory $responseFactory, AlertHelper $alertHelper, Language $lang) {
        $this->userRepository = $userRepository;
        $this->auth = $auth;
        $this->responseFactory = $responseFactory;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
    }

    /**
     * Returns a json response with all users from database
     * @return JsonResponse All users from database
     */
    public function index() {
        $users = $this->userRepository->getUsers();
        return $this->responseFactory->json($users);
    }

    /**
     * Returns a html response with login and register page content
     * @return HtmlResponse Login and register page
     */
    public function loginPage() {
        return $this->responseFactory->html('user/login.html.twig');
    }

    /**
     * Returns a html response with forgotten password page content
     * @return HtmlResponse Forgotten password page
     */
    public function forgottenPasswordPage() {
        return $this->responseFactory->html('user/forgottenPassword.html.twig');
    }

    public function resetPasswordPage() {}

    /**
     * Tries to log user in and redirects him after
     * @return RedirectResponse Dashboard on successful login,
     *                          login page on unsuccessful
     */
    public function login() {
        if ($this->auth->login($_POST['login_email'], $_POST['login_password'])) {
            var_dump($this->lang);
            $this->alertHelper->success($this->lang->get('alerts.login.success'));
            return redirect('/trips');
        }

        $this->alertHelper->error($this->lang->get('alerts.login.wrong'));
        return redirect('/login');
    }

    /**
     * Logs user out and redirects him to login page
     * @return RedirectResponse Login page
     */
    public function logout() {
        $this->auth->logout();
        $this->alertHelper->success($this->lang->get('alerts.logout.success'));
        return redirect('/login');
    }

    /**
     * Creates new user and redirects him to dashboard
     * @return RedirectResponse Login page
     */
    public function register() {
        // TODO: Registration
        return redirect('/trips');
    }

    /**
     * Sends email with instructions to reset password
     * and redirects user back to login page
     * @return RedirectResponse Login page
     */
    public function forgottenPassword() {
        // TODO: Forgotten password
        return redirect('/login');
    }

    public function resetPassword() {}

    /**
     * Returns a html response with profile page content
     * @return HtmlResponse Profile page
     */
    public function profile() {
        return $this->responseFactory->html('user/profile.html.twig');
    }
}