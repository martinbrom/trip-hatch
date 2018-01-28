<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Instance of class provides simple functions,
 * that fetch data from Database,
 * to be used in TripController
 * @package App\Repositories
 * @author Martin Brom
 */
class TripCommentsRepository
{
    /** @var Repository Instance of base repository used for communicating with database */
    private $baseRepository;

    /**
     * Creates TripCommentsRepository instance and injects Repository instance
     * @param Repository $baseRepository
     */
    public function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * @param $comment_id
     * @return array
     */
    public function getComment($comment_id) {
        $query = "SELECT * FROM trip_comments
                WHERE trip_comments.id = :comment_id";
        $data = ['comment_id' => $comment_id];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @param $trip_id
     * @return array
     */
    public function getComments($trip_id) {
        $query = "SELECT trip_comments.id AS id, trip_comments.content AS content, users.display_name AS user_name,
                users.email AS user_email, images.path AS image_path, images.description AS image_description
                FROM trip_comments
                INNER JOIN user_trip_xref ON trip_comments.user_trip_id = user_trip_xref.id
                INNER JOIN users ON user_trip_xref.user_id = users.id
                INNER JOIN images ON users.image_id = images.id
                WHERE user_trip_xref.trip_id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetchAll($query, $data);
    }

    /**
     * @param $user_trip_id
     * @param $content
     * @return bool
     */
    public function create($user_trip_id, $content) {
        $query = "INSERT INTO `trip_comments`(`id`, `content`,
                `user_trip_id`)
                VALUES (NULL, :content,
                :user_trip_id)";
        $data = ['content' => $content, 'user_trip_id' => $user_trip_id];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @param $comment_id
     * @return bool
     */
    public function delete($comment_id) {
        $query = "DELETE FROM trip_comments
                WHERE id = :comment_id";
        $data = ['comment_id' => $comment_id];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @return int
     */
    public function getNewCount(): int {
        $date = date('Y-m-d H:i:s', strtotime('-1 week'));
        $query = "SELECT COUNT(*) as count FROM trip_comments
                WHERE created_at >= '$date'";
        return $this->baseRepository->fetch($query)['count'];
    }
}
