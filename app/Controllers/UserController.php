<?php

namespace App\Controllers;

class UserController
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
}