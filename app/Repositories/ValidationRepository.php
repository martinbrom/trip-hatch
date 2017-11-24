<?php

namespace App\Repositories;

use Core\Database\Repository;

class ValidationRepository
{
    /** @var Repository */
    private $baseRepository;

    /**
     * ValidationRepository constructor.
     * @param Repository $baseRepository
     */
    function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * @param $value
     * @param $table
     * @param $column
     * @return bool
     */
    public function exists($value, $table, $column): bool {
        return $this->itemCount($value, $table, $column) > 0;
    }

    /**
     * @param $value
     * @param $table
     * @param $column
     * @return bool
     */
    public function unique($value, $table, $column): bool {
        return $this->itemCount($value, $table, $column) == 0;
    }

    /**
     * @param $value
     * @param $table
     * @param $column
     * @return int
     */
    public function itemCount($value, $table, $column): int {
        $query = "SELECT COUNT('id') as count FROM $table
                WHERE $column = :value";
        $data = ['value' => $value];
        return $this->baseRepository->fetch($query, $data)["count"];
    }
}