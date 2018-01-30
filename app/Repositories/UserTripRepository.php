<?php

namespace App\Repositories;

use App\Enums\UserTripRoles;
use Core\Database\Repository;

/**
 * Class UserTripRepository
 * @package App\Repositories
 * @author Martin Brom
 */
class UserTripRepository
{
    /** @var Repository */
    private $baseRepository;

    /**
     * UserTripRepository constructor.
     * @param Repository $baseRepository
     */
    function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * @param $trip_id
     * @return array
     */
    public function getStaff($trip_id) {
        $role = UserTripRoles::ORGANISER;
        $query = "SELECT * FROM user_trip_xref
                WHERE trip_id = :trip_id AND role >= :role";
        $data = ['trip_id' => $trip_id, 'role' => $role];
        return $this->baseRepository->fetchAll($query, $data);
    }

    /**
     * @param $trip_id
     * @return array
     */
    public function getTravellers($trip_id): array {
        return $this->getByRole($trip_id, UserTripRoles::TRAVELLER);
    }

    /**
     * @param $trip_id
     * @return array
     */
    public function getOrganisers($trip_id): array {
        return $this->getByRole($trip_id, UserTripRoles::ORGANISER);
    }

    /**
     * @param $trip_id
     * @param $role
     * @return array
     */
    public function getByRole($trip_id, $role): array {
        $query = "SELECT user_trip_xref.id AS id, user_trip_xref.role AS role,
                users.email AS email, users.display_name AS display_name
                FROM user_trip_xref
                JOIN users ON users.id = user_trip_xref.user_id
                WHERE trip_id = :trip_id AND role = :role";
        $data = ['trip_id' => $trip_id, 'role' => $role];
        return $this->baseRepository->fetchAll($query, $data);
    }

    /**
     * @param $user_id
     * @param $trip_id
     * @return array
     */
    public function getRole($user_id, $trip_id) {
        $query = "SELECT role FROM user_trip_xref
                WHERE user_id = :user_id
                AND trip_id = :trip_id";
        $data = ['user_id' => $user_id, 'trip_id' => $trip_id];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @param $user_trip_id
     * @return bool
     */
    public function removeTraveller($user_trip_id) {
        $query = "DELETE FROM user_trip_xref
                WHERE id = :id";
        $data = ['id' => $user_trip_id];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $user_trip_id
     * @return bool
     */
    public function isExactlyTraveller($user_trip_id): bool {
        return $this->isExactlyRole($user_trip_id, UserTripRoles::TRAVELLER);
    }

    /**
     * @param $user_trip_id
     * @return bool
     */
    public function isExactlyOrganiser($user_trip_id) {
        return $this->isExactlyRole($user_trip_id, UserTripRoles::ORGANISER);
    }

    /**
     * @param $user_trip_id
     * @param $role
     * @return bool
     */
    public function isExactlyRole($user_trip_id, $role): bool {
        $query = "SELECT COUNT(*) as count FROM user_trip_xref
                WHERE id = :id AND role = :role";
        $data = ['id' => $user_trip_id, 'role' => $role];
        return $this->baseRepository->fetch($query, $data)['count'] >= 1;
    }

    /**
     * @param $user_id
     * @param $trip_id
     * @return bool
     */
    public function setTripOwner($user_id, $trip_id) {
        $role = UserTripRoles::OWNER;
        $query = "INSERT INTO `user_trip_xref` (
                `id`, `user_id`, `trip_id`, `role`,
                `created_at`, `updated_at`)
                VALUES (NULL, :user_id, :trip_id, :role,
                CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $data = ['trip_id' => $trip_id, 'user_id' => $user_id, 'role' => $role];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $user_trip_id
     * @return bool
     */
    public function setTraveller($user_trip_id) {
        return $this->setRole($user_trip_id, UserTripRoles::TRAVELLER);
    }

    /**
     * @param $user_trip_id
     * @return bool
     */
    public function setOrganiser($user_trip_id) {
        return $this->setRole($user_trip_id, UserTripRoles::ORGANISER);
    }

    // TODO: Implement on the website - transfer ownership
    /**
     * @param $user_trip_id
     * @return bool
     */
    public function setOwner($user_trip_id) {
        return $this->setRole($user_trip_id, UserTripRoles::OWNER);
    }

    /**
     * @param $user_trip_id
     * @param $role
     * @return bool
     */
    public function setRole($user_trip_id, $role) {
        $query = "UPDATE user_trip_xref
                SET role = :role
                WHERE id = :id";
        $data = ['role' => $role, 'id' => $user_trip_id];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $user_id
     * @param $trip_id
     * @param $role
     * @return bool
     */
    public function create($user_id, $trip_id, $role) {
        $query = "INSERT INTO `user_trip_xref` (
                `id`, `user_id`, `trip_id`, `role`,
                `created_at`, `updated_at`)
                VALUES (NULL, :user_id, :trip_id, :role,
                CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $data = ['trip_id' => $trip_id, 'user_id' => $user_id, 'role' => $role];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $user_id
     * @param $trip_id
     * @return mixed
     */
    public function hasAccess($user_id, $trip_id) {
        return $this->getRole($user_id, $trip_id) != NULL;
    }

    /**
     * @param $user_id
     * @param $trip_id
     * @return array
     */
    public function getID($user_id, $trip_id) {
        $query = "SELECT user_trip_xref.id as id
                FROM user_trip_xref
                WHERE user_id = :user_id AND trip_id = :trip_id";
        $data = ['user_id' => $user_id, 'trip_id' => $trip_id];
        return $this->baseRepository->fetch($query, $data);
    }
}