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
        $role = UserTripRoles::TRAVELLER;
        $query = "SELECT * FROM user_trip_xref
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
        $role = UserTripRoles::TRAVELLER;
        $query = "SELECT COUNT(*) as count FROM user_trip_xref
                WHERE id = :id AND role = :role";
        $data = ['id' => $user_trip_id, 'role' => $role];
        return $this->baseRepository->fetch($query, $data)['count'] >= 1;
    }
}