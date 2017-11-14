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
        $user = $this->userRepository->getUser($email);

        if (!password_verify($password, $hash)) return false;

        $this->session->put('user', $user);
        return true;
    }

    public function isLogged() {
        return $this->session->exists('user');
    }

    public function logout() {
        $this->session->delete('user');
    }
}