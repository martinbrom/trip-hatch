<?php

namespace App\Repositories;

class TripRepository
{
    private $baseRepository;

    public function __construct(\Core\Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    public function getActions($day_id) {
        $query = "SELECT actions.*, action_types.icon_class, action_types.color_class FROM actions
                INNER JOIN action_types ON actions.action_type_id = action_types.id
                WHERE actions.day_id = :day_id";
        $data = ['day_id' => $day_id];
        return $this->baseRepository->fetchAll($query, $data);
    }
    
    public function getDays($trip_id) {
        $query = "SELECT days.*, images.path, images.description FROM days
                INNER JOIN images ON days.image_id = images.id
                WHERE trip_id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetchAll($query, $data);
    }

    public function getTrip($trip_id) {
        $query = "SELECT * FROM trips WHERE id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetch($query, $data);
    }

    public function getTrips($user_id) {
        $query = "SELECT trips.*, images.path, images.description FROM user_trip_xref
                INNER JOIN trips ON trips.id = user_trip_xref.trip_id
                INNER JOIN images ON images.id = trips.image_id
                WHERE user_trip_xref.user_id = :user_id";
        $data = ['user_id' => $user_id];
        return $this->baseRepository->fetchAll($query, $data);
    }
}