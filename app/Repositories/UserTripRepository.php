<?php

namespace App\Repositories;

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
     * @param $user_id
     * @param $trip_id
     * @return bool
     */
    public function isOwner($user_id, $trip_id): bool {
        return $this->hasRole($user_id, $trip_id, 2);
    }

    /**
     * @param $user_id
     * @param $trip_id
     * @return bool
     */
    public function isOrganiser($user_id, $trip_id): bool {
        return $this->hasRole($user_id, $trip_id, 1);
    }

    /**
     * @param $user_id
     * @param $trip_id
     * @return bool
     */
    public function isTraveller($user_id, $trip_id): bool {
        return $this->hasRole($user_id, $trip_id, 0);
    }

    /**
     * @param $user_id
     * @param $trip_id
     * @param $role
     * @return bool
     */
    public function hasRole($user_id, $trip_id, $role): bool {
        $query = "SELECT COUNT(*) as count FROM user_trip_xref
                WHERE user_id = :user_id
                AND trip_id = :trip_id
                AND role >= :role";
        $data = ['user_id' => $user_id, 'trip_id' => $trip_id, 'role' => $role];
        return $this->baseRepository->fetch($query, $data)['count'] >= 1;
    }
}