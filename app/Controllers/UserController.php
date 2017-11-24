<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Core\AlertHelper;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\RedirectResponse;
use Core\Http\Response\Response;
use Core\Language\Language;

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

    /**
     * Creates new instance and injects user repository and auth
     * @param UserRepository $userRepository Instance for getting data from database
     * @param Auth $auth Instance for user authentication
     * @param AlertHelper $alertHelper
     * @param Language $lang
     */
    function __construct(UserRepository $userRepository, Auth $auth, AlertHelper $alertHelper, Language $lang) {
        $this->userRepository = $userRepository;
        $this->auth = $auth;
        $this->alertHelper = $alertHelper;
        $this->lang = $lang;
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

    public function resetPasswordPage() {}

    /**
     * Tries to log user in and redirects him after
     * @return RedirectResponse Dashboard on successful login,
     *                          login page on unsuccessful
     */
    public function login() {
        if ($this->auth->login($_POST['login_email'], $_POST['login_password'])) {
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
     * @return RedirectResponse Login page
     */
    public function register() {
        $email = $_POST['register_email'];
        $password = $_POST['register_password'];
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
     * @return RedirectResponse Login page
     */
    public function forgottenPassword() {
        // TODO: Forgotten password
        return $this->route('login');
    }

    public function resetPassword() {}
}