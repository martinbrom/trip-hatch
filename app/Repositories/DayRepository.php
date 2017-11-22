<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Class DayRepository
 * @package App\Repositories
 * @author Martin Brom
 */
class DayRepository
{
    /** @var Repository */
    private $baseRepository;

    /**
     * DayRepository constructor.
     * @param Repository $baseRepository
     */
    function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * Returns all days for given trip from the database
     * @param int $trip_id ID of the trip
     * @return array Days for given trip
     */
    public function getDays(int $trip_id): array {
        $query = "SELECT days.*, images.path, images.description FROM days
                INNER JOIN images ON days.image_id = images.id
                WHERE trip_id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetchAll($query, $data);
    }

    /**
     * @param int $trip_id
     * @param int $day_id
     * @return bool
     */
    public function tripHasDay(int $trip_id, int $day_id) {
        $query = "SELECT COUNT(*) FROM days
                WHERE trip_id = :trip_id AND id = :day_id";
        $data = ['trip_id' => $trip_id, 'day_id' => $day_id];
        return $this->baseRepository->fetch($query, $data)['count'] >= 1;
    }
}