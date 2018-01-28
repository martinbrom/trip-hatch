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
                WHERE trip_id = :trip_id
                ORDER BY days.order";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetchAll($query, $data);
    }

    /**
     * @param int $day_id
     * @return array
     */
    public function getDay(int $day_id): array {
        $query = "SELECT days.*, images.path, images.description FROM days
                INNER JOIN images ON days.image_id = images.id
                WHERE days.id = :day_id";
        $data = ['day_id' => $day_id];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @param int $trip_id
     * @param int $day_id
     * @return array
     */
    public function getTripDay(int $trip_id, int $day_id): array {
        $query = "SELECT days.*, images.path, images.description FROM days
                INNER JOIN images ON days.image_id = images.id
                WHERE days.id = :day_id AND days.trip_id = :trip_id";
        $data = ['trip_id' => $trip_id, 'day_id' => $day_id];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @return int
     */
    public function getNewCount(): int {
        $date = date('Y-m-d H:i:s', strtotime('-1 week'));
        $query = "SELECT COUNT(*) as count FROM days
                WHERE created_at >= '$date'";
        return $this->baseRepository->fetch($query)['count'];
    }

    /**
     * @param int $trip_id
     * @param int $day_id
     * @return bool
     */
    public function tripHasDay(int $trip_id, int $day_id) {
        $query = "SELECT COUNT(*) as count FROM days
                WHERE trip_id = :trip_id AND id = :day_id";
        $data = ['trip_id' => $trip_id, 'day_id' => $day_id];
        return $this->baseRepository->fetch($query, $data)['count'] >= 1;
    }

    /**
     * @param int $trip_id
     * @return mixed
     */
    public function getDayCount(int $trip_id) {
        $query = "SELECT COUNT(*) as count FROM days
                WHERE trip_id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetch($query, $data)['count'];
    }

    /**
     * @param int $trip_id
     * @param int $order
     * @return bool
     */
    public function create(int $trip_id, int $order) {
        $query = "INSERT INTO `days`(
                `id`, `title`, `order`, `image_id`, `trip_id`,
                `created_at`, `updated_at`)
                VALUES (NULL, 'New day', :order, 2, :trip_id,
                CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $data = ['trip_id' => $trip_id, 'order' => $order];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @return string
     */
    public function lastInsertId() {
        return $this->baseRepository->lastInsertId();
    }

    /**
     * @return array
     */
    public function getLastInsertDay() {
        return $this->getDay($this->lastInsertId());
    }

    /**
     * @param $day_id
     * @param $title
     * @param $image_id
     * @return bool
     */
    public function edit($day_id, $title, $image_id) {
        $query = "UPDATE days
                SET title = :title, image_id = :image_id,
                updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        $data = ['title' => $title, 'id' => $day_id, 'image_id' => $image_id];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $day_id
     * @return bool
     */
    public function delete($day_id) {
        $query = "DELETE FROM days WHERE id = :id";
        $data = ['id' => $day_id];
        return $this->baseRepository->run($query, $data);
    }
}