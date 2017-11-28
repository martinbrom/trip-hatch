<?php

namespace Core;

use App\Enums\UserTripRoles;
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

    /** @var array */
    private $roles;

    /**
     * Auth constructor.
     * @param Session $session
     * @param UserRepository $userRepository
     * @param UserTripRepository $userTripRepository
     */
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
     *
     */
    public function logout() {
        $this->session->delete('user');
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
     * @param $trip_id
     */
    private function loadRoleArray($trip_id) {
        $this->roles = [false, false, false];
        $user_id = $this->session->get('user.id');
        $result = $this->userTripRepository->getRole($user_id, $trip_id);

        $role = empty($result) ? -1 : $result['role'];

        for ($i = 0; $i <= $role; $i++)
            $this->roles[$i] = true;
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

        if (empty($this->roles))
            $this->loadRoleArray($trip_id);

        return $this->roles[UserTripRoles::OWNER];
    }

    /**
     * @param $trip_id
     * @return bool
     */
    public function isOrganiser($trip_id) {
        if (!$this->isLogged())
            return false;

        if (empty($this->roles))
            $this->loadRoleArray($trip_id);

        return $this->roles[UserTripRoles::ORGANISER];
    }

    /**
     * @param $trip_id
     * @return bool
     */
    public function isTraveller($trip_id) {
        if (!$this->isLogged())
            return false;

        if (empty($this->roles))
            $this->loadRoleArray($trip_id);

        return $this->roles[UserTripRoles::TRAVELLER];
    }

    /**
     * @param $trip_id
     * @return array
     */
    public function getRole($trip_id) {
        $user_id = $this->session->get('user.id');
        return $this->userTripRepository->getRole($user_id, $trip_id);
    }
}