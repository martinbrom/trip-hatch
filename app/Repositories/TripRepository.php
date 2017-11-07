<?php

namespace App\Repositories;

/**
 * Class TripRepository
 * @package App\Repositories
 * @author Martin Brom
 */
class TripRepository
{
    /** @var \Core\Repository  */
    private $baseRepository;

    /**
     * TripRepository constructor.
     * @param \Core\Repository $baseRepository
     */
    public function __construct(\Core\Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * Returns all actions for given day from the database
     * @param int $day_id ID of the day
     * @return array Actions for given day
     */
    public function getActions(int $day_id): array {
        $query = "SELECT actions.*, action_types.icon_class, action_types.color_class FROM actions
                INNER JOIN action_types ON actions.action_type_id = action_types.id
                WHERE actions.day_id = :day_id";
        $data = ['day_id' => $day_id];
        return $this->baseRepository->fetchAll($query, $data);
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
}