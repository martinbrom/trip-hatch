<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Class ActionTypeRepository
 * @package App\Repositories
 * @author Martin Brom
 */
class ActionTypeRepository
{
    /** @var Repository */
    private $baseRepository;

    /**
     * ActionTypeRepository constructor.
     * @param Repository $baseRepository
     */
    function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * Returns all action types from the database
     * @return array All action types
     */
    public function getAll() {
        $query = "SELECT * FROM action_types";
        return $this->baseRepository->fetchAll($query);
    }
}