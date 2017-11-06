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

    // TODO: Validate and sanitize input during request
    public function index() {
        $data = $this->userRepository->complexEmails();
        echo '<pre>';
        var_dump($data);
        echo '</pre>';

        $data = $this->userRepository->firstWithImage();
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
    }

    public function loginPage() {
        View::render('user/login.html.twig');
    }

    public function registerPage() {
        View::render('user/register.html.twig');
    }

    public function login() {}
    public function register() {}
}