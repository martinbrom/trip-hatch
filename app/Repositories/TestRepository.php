<?php

namespace App\Repositories;

use App\Models\TestModel;

class TestRepository
{
    private $baseRepository;
    private $table = 'tests';
    // private $primaryKey = "id";

    public function __construct(\Core\Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    public function getAll() {
        $data = $this->baseRepository->getAll($this->table);
        $models = [];

        foreach ($data as $item) {
            $model = new TestModel($item);
            $models []= $model;
        }

        return $models;
    }
}