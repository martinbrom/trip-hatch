<?php

namespace App\Repositories;

class TripRepository
{
    private $baseRepository;
    private $table = 'trips';

    public function __construct(\Core\Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    public function first($params) {
        return $this->baseRepository->select(
            $this->table,
            ['trips.id = ' . $params['id']],
            [],
            ['images' => ['type' => 'INNER', 'condition' => 'trips.image_id = images.id']],
            1)[0];
    }
}