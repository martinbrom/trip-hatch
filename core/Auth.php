<?php

namespace Core;

use App\Repositories\UserRepository;

class Auth
{
    private $session;
    private $userRepository;

    public function __construct(Session $session, UserRepository $userRepository) {
        $this->session = $session;
        $this->userRepository = $userRepository;
    }

    // TODO: Maybe add 'remember me' for logging in
    public function login($email, $password) {
        $hash = bcrypt($password); // TODO: Password hashing
        $user = $this->userRepository->getUser($email, $password);

        if ($user == null) return false;

        $this->session->put('user', 'stuff');
        return true;
    }

    public function logout() {
        $this->session->delete('user');
    }
}