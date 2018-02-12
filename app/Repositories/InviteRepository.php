<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Instance of class provides simple functions,
 * that fetch invite data from Database,
 * to be used in InviteController
 * @package App\Repositories
 * @author Martin Brom
 */
class InviteRepository
{
    /** @var Repository Instance of base repository used for communicating with database */
    private $baseRepository;

    /**
     * Creates InviteRepository instance and injects Repository instance
     * @param Repository $baseRepository
     */
    public function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * @param $trip_id
     * @param $email
     * @param $message
     * @param $token
     * @return bool
     */
    public function create($trip_id, $email, $message, $token) {
        $query = "INSERT INTO `invites`(
                `id`, `email`, `message`, `token`, `trip_id`,
                `created_at`, `updated_at`)
                VALUES (NULL, :email, :message, :token, :trip_id,
                CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $data = ['trip_id' => $trip_id, 'email' => $email, 'message' => $message, 'token' => $token];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $trip_id
     * @param $email
     * @return bool
     */
    public function canInvite($trip_id, $email) {
        // all invites from the last 10 minutes
        $query = "SELECT COUNT(ID) AS 'count' FROM `invites`
                WHERE `email` = :email
                AND `trip_id` = :trip_id
                AND `created_at` >= (now() - INTERVAL 10 MINUTE)";
        $data = ['trip_id' => $trip_id, 'email' => $email];
        return $this->baseRepository->fetch($query, $data)['count'] == 0;
    }

    /**
     * @param $token
     * @return array
     */
    public function getInvite($token) {
        $query = "SELECT * FROM `invites`
                WHERE `token` = :token";
        $data = ['token' => $token];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id) {
        $query = "DELETE FROM `invites`
                WHERE id = :id";
        $data = ['id' => $id];
        return $this->baseRepository->run($query, $data);
    }
}
