<?php

namespace App\Controllers;

use Core\Http\Controller;
use Core\Http\Response\HtmlResponse;
use Core\Http\Response\JsonResponse;

class UserController extends Controller
{
    private $userRepository;
    
    function __construct(\App\Repositories\UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index() {
        $users = $this->userRepository->getUsers();
        // var_dump($users);
        return new JsonResponse($users);
    }

    public function loginPage() {
        return new HtmlResponse('user/login.html.twig');
    }

    public function forgottenPasswordPage() {
        return new HtmlResponse('user/forgottenPassword.html.twig');
    }

    public function login() {}
    public function register() {}
    public function forgottenPassword() {

    }
}