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
        /*$data = [
            'email' => 'testmail@a.a',
            'password' => 'randompasswordhash',
            'image_id' => 1
        ];

        $this->userRepository->insert($data);*/

        // $data = $this->userRepository->all();

        /*$data = $this->userRepository->first();
        echo '<pre>';
        var_dump($data);
        echo '</pre>';

        $data['display_name'] = "Test display name";
        $this->userRepository->update($data);

        $data = $this->userRepository->first();
        echo '<pre>';
        var_dump($data);
        echo '</pre>';*/

        /*$data = $this->userRepository->first();
        $this->userRepository->delete($data);*/
    }
}