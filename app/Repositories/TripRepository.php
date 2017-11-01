<?php

namespace App\Repositories;

class TripRepository
{
    private $baseRepository;

    public function __construct(\Core\Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }
    
    public function getDays($trip_id) {
        $query = "SELECT * FROM days INNER JOIN images ON days.image_id = images.id WHERE trip_id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetchAll($query, $data);
    }

    public function getTrip($trip_id) {
        $query = "SELECT * FROM trips WHERE id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetch($query, $data);
    }

    public function getTrips($user_id) {
        $query = "SELECT trips.* FROM user_trip_xref INNER JOIN trips ON trips.id = user_trip_xref.trip_id WHERE user_trip_xref.user_id = :user_id";
        $data = ['user_id' => $user_id];
        return $this->baseRepository->fetchAll($query, $data);
    }
}