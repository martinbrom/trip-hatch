<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Instance of class provides simple functions,
 * that fetch data from Database, to be used in TripController
 * @package App\Repositories
 * @author Martin Brom
 */
class TripRepository
{
    /** @var Repository Instance of base repository used for communicating with database */
    private $baseRepository;

    /**
     * Creates TripRepository instance and injects Repository instance
     * @param Repository $baseRepository
     */
    public function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * Returns first trip for given trip_id from the database
     * @param int $trip_id ID of the trip
     * @return array Trip for given trip_id
     */
    public function getTrip(int $trip_id): array {
        $query = "SELECT * FROM trips WHERE id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * Returns all trips for given user from the database
     * @param int $user_id ID of the user
     * @return array Trips for given user
     */
    public function getTrips(int $user_id): array {
        $query = "SELECT trips.*, images.path, images.description FROM user_trip_xref
                INNER JOIN trips ON trips.id = user_trip_xref.trip_id
                INNER JOIN images ON images.id = trips.image_id
                WHERE user_trip_xref.user_id = :user_id";
        $data = ['user_id' => $user_id];
        return $this->baseRepository->fetchAll($query, $data);
    }

    /**
     * @param int $limit
     * @param int $page
     * @return array
     */
    public function getNewTrips($limit = 100, $page = 0): array {
        $date = date('Y-m-d H:i:s', strtotime('-1 week'));
        $offset = $page * $limit;
        $query = "SELECT * FROM trips
                WHERE created_at <= '$date'
                LIMIT $limit OFFSET $offset";
        return $this->baseRepository->fetchAll($query);
    }

    /**
     * @return int
     */
    public function getNewCount(): int {
        $date = date('Y-m-d H:i:s', strtotime('-1 week'));
        $query = "SELECT COUNT(*) as count FROM trips
                WHERE created_at >= '$date'";
        return $this->baseRepository->fetch($query)['count'];
    }

    /**
     * @param $public_url
     * @return array
     */
    public function getTripPublic($public_url) {
        $query = "SELECT * FROM trips WHERE public_url = :public_url";
        $data = ['public_url' => $public_url];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @param int $trip_id
     * @return bool
     */
    public function publishTrip(int $trip_id) {
        $public_url = substr($trip_id . token(31), 0, 32);
        $query = "UPDATE trips SET public_url = :public_url WHERE id = :trip_id";
        $data = ['trip_id' => $trip_id, 'public_url' => $public_url];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param int $trip_id
     * @return bool
     */
    public function classifyTrip(int $trip_id) {
        $query = "UPDATE trips SET public_url = NULL WHERE id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $title
     * @return bool
     */
    public function createTrip($title) {
        $query = "INSERT INTO `trips` (
                `id`, `title`, `start_date`, `end_date`,
                `image_id`, `created_at`, `updated_at`)
                VALUES (NULL, :title, NULL, NULL,
                '2', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        $data = ['title' => $title];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $trip_id
     * @param $title
     * @param $start_date
     * @param $image_id
     * @return bool
     */
    public function edit($trip_id, $title, $start_date, $image_id) {
        $query = "UPDATE trips
                SET title = :title, image_id = :image_id,
                start_date = :start_date, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        $data = ['title' => $title, 'id' => $trip_id, 'image_id' => $image_id, 'start_date' => $start_date];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $trip_id
     * @return bool
     */
    public function delete($trip_id) {
        $query = "DELETE FROM trips
                WHERE id = :id";
        $data = ['id' => $trip_id];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @return string
     */
    public function lastInsertID() {
        return $this->baseRepository->lastInsertId();
    }
}