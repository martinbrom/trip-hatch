<?php

namespace Core;

use App\Repositories\UserRepository;
use App\Repositories\UserTripRepository;

/**
 * Class Auth
 * @package Core
 * @author Martin Brom
 */
class Auth
{
    /** @var Session */
    private $session;

    /** @var UserRepository */
    private $userRepository;

    /** @var UserTripRepository */
    private $userTripRepository;

    public function __construct(Session $session, UserRepository $userRepository, UserTripRepository $userTripRepository) {
        $this->session = $session;
        $this->userRepository = $userRepository;
        $this->userTripRepository = $userTripRepository;
    }

    // TODO: Maybe add 'remember me' for logging in
    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public function login($email, $password) {
        $user = $this->userRepository->getUser($email);
        
        if (empty($user) || !password_verify($password, $user['password'])) return false;

        $this->session->set('user', $user);
        return true;
    }

    /**
     * @param $email
     * @param $password
     * @return bool
     */
    public function register($email, $password) {
        return $this->userRepository->createUser($email, bcrypt($password));
    }

    /**
     * @return bool
     */
    public function isLogged() {
        return $this->session->exists('user');
    }

    /**
     * @return bool
     */
    public function isAdmin() {
        if (!$this->isLogged())
            return false;

        return $this->session->get('user.is_admin');
    }

    /**
     * @param $trip_id
     * @return bool
     */
    public function isOwner($trip_id) {
        if (!$this->isLogged())
            return false;

        $user_id = $this->session->get('user.id');
        return $this->userTripRepository->isOwner($user_id, $trip_id);
    }

    /**
     * @param $trip_id
     * @return bool
     */
    public function isOrganiser($trip_id) {
        if (!$this->isLogged())
            return false;

        $user_id = $this->session->get('user.id');
        return $this->userTripRepository->isOrganiser($user_id, $trip_id);
    }

    /**
     * @param $trip_id
     * @return bool
     */
    public function isTraveller($trip_id) {
        if (!$this->isLogged())
            return false;

        $user_id = $this->session->get('user.id');
        return $this->userTripRepository->isTraveller($user_id, $trip_id);
    }

    /**
     *
     */
    public function logout() {
        $this->session->delete('user');
    }
}