<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Class ActionRepository
 * @package App\Repositories
 * @author Martin Brom
 */
class ActionRepository
{
    /** @var Repository */
    private $baseRepository;

    /**
     * ActionRepository constructor.
     * @param Repository $baseRepository
     */
    function __construct(Repository $baseRepository) {
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
}