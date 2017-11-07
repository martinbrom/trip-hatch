<?php

namespace App\Controllers;

use Core\Controller;
use Core\View;

class UserController extends Controller
{
    private $userRepository;
    
    function __construct(\App\Repositories\UserRepository $userRepository) {
        $this->userRepository = $userRepository;
    }

    public function index() {
        $users = $this->userRepository->getUsers();
        var_dump($users);
    }

    public function loginPage() {
        View::render('user/login.html.twig');
    }

    public function login() {}
    public function register() {}
}