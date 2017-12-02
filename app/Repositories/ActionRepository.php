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

    /**
     * @return array
     */
    public function getLastInsertAction() {
        $query = "SELECT actions.*, action_types.icon_class, action_types.color_class FROM actions
                INNER JOIN action_types ON actions.action_type_id = action_types.id
                WHERE actions.id = :id";
        $data = ['id' => $this->lastInsertId()];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @param int $day_id
     * @return mixed
     */
    public function getActionCount(int $day_id) {
        $query = "SELECT COUNT(*) as count FROM actions
                WHERE day_id = :day_id";
        $data = ['day_id' => $day_id];
        return $this->baseRepository->fetch($query, $data)['count'];
    }

    /**
     * @param $title
     * @param $content
     * @param int $order
     * @param int $day_id
     * @param int $action_type_id
     * @return bool
     */
    public function create($title, $content, int $order, int $day_id, int $action_type_id) {
        $query = "INSERT INTO `actions`(`id`, `title`, `content`,
                `order`, `day_id`, `action_type_id`,
                `created_at`, `updated_at`, `deleted_at`)
                VALUES (NULL, :title, :content,
                :order, :day_id, :action_type_id,
                CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, NULL)";
        $data = [
            'title' => $title,
            'content' => $content,
            'order' => $order,
            'day_id' => $day_id,
            'action_type_id' => $action_type_id
        ];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @return string
     */
    public function lastInsertId() {
        return $this->baseRepository->lastInsertId();
    }
}