<?php

namespace App\Controllers;

use App\Repositories\UserRepository;
use Core\Auth;
use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;

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

    /**
     * Creates new instance and injects user repository and auth
     * @param UserRepository $userRepository Instance for getting data from database
     * @param Auth $auth Instance for user authentication
     */
    function __construct(UserRepository $userRepository, Auth $auth) {
        $this->userRepository = $userRepository;
        $this->auth = $auth;
    }

    /**
     * Returns a json response with all users from database
     * @return JsonResponse All users from database
     */
    public function index() {
        $users = $this->userRepository->getUsers();
        // var_dump($users);
        return new JsonResponse($users);
    }

    /**
     * Returns a html response with login and register page content
     * @return HtmlResponse Login and register page
     */
    public function loginPage() {
        return new HtmlResponse('user/login.html.twig');
    }

    /**
     * Returns a html response with forgotten password page content
     * @return HtmlResponse Forgotten password page
     */
    public function forgottenPasswordPage() {
        return new HtmlResponse('user/forgottenPassword.html.twig');
    }

    /**
     * Tries to log user in and redirects him after
     * @return RedirectResponse Dashboard on successful login,
     *                          login page on unsuccessful
     */
    public function login() {
        if ($this->auth->login($_POST['login_email'], $_POST['login_password'])) {
            // TODO: Add success message
            return new RedirectResponse('/trips');
        }

        // TODO: Add error message
        return new RedirectResponse('/login');
    }

    /**
     * Logs user out and redirects him to login page
     * @return RedirectResponse Login page
     */
    public function logout() {
        $this->auth->logout();
        return new RedirectResponse('/login');
    }

    public function register() {}
    public function forgottenPassword() {}
    public function profile() {}
}