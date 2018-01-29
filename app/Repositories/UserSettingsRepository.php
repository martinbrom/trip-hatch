<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Class UserSettingsRepository
 * @package App\Repositories
 * @author Martin Brom
 */
class UserSettingsRepository
{
    /** @var Repository */
    private $baseRepository;

    /**
     * UserSettingsRepository constructor.
     * @param Repository $baseRepository
     */
    function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * @param $user_id
     * @param $display_name
     * @return bool
     */
    public function changeDisplayName($user_id, $display_name) {
        $query = "UPDATE users
                SET display_name = :display_name
                WHERE id = :id";
        $data = ['id' => $user_id, 'display_name' => $display_name];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $user_id
     * @param $password
     * @return bool
     */
    public function changePassword($user_id, $password) {
        $query = "UPDATE users
                SET `password` = :password
                WHERE `id` = :id";
        $data = ['id' => $user_id, 'password' => $password];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $email
     * @param $token
     * @return bool
     */
    public function setPasswordResetToken($email, $token) {
        $query = "UPDATE users
                SET password_reset_token = :token
                WHERE email = :email";
        $data = ['email' => $email, 'token' => $token];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $email
     * @param $token
     * @return bool
     */
    public function passwordResetExists($email, $token) {
        $query = "SELECT count(ID) as count
                FROM users
                WHERE email = :email
                AND password_reset_token = :token";
        $data = ['email' => $email, 'token' => $token];
        return $this->baseRepository->fetch($query, $data)['count'] > 0;
    }

    /**
     * @param $email
     * @param $hash
     * @return bool
     */
    public function resetPassword($email, $hash) {
        $query = "UPDATE users
                SET password = :hash, password_reset_token = NULL
                WHERE email = :email";
        $data = ['email' => $email, 'hash' => $hash];
        return $this->baseRepository->run($query, $data);
    }
}