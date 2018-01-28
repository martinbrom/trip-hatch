<?php

namespace App\Repositories;

use Core\Database\Repository;

/**
 * Instance of class provides simple functions,
 * that fetch data from Database,
 * to be used in TripFilesController
 * @package App\Repositories
 * @author Martin Brom
 */
class TripFilesRepository
{
    /** @var Repository Instance of base repository used for communicating with database */
    private $baseRepository;

    /**
     * Creates TripFilesRepository instance and injects Repository instance
     * @param Repository $baseRepository
     */
    public function __construct(Repository $baseRepository) {
        $this->baseRepository = $baseRepository;
    }

    /**
     * @param $file_id
     * @return array
     */
    public function getFile($file_id) {
        $query = "SELECT * FROM trip_files
                WHERE id = :file_id";
        $data = ['file_id' => $file_id];
        return $this->baseRepository->fetch($query, $data);
    }

    /**
     * @param $trip_id
     * @return array
     */
    public function getFiles($trip_id) {
        $query = "SELECT * FROM trip_files
                WHERE trip_id = :trip_id";
        $data = ['trip_id' => $trip_id];
        return $this->baseRepository->fetchAll($query, $data);
    }

    /**
     * @param $file_id
     * @return bool
     */
    public function delete($file_id) {
        $query = "DELETE FROM trip_files
                WHERE id = :file_id";
        $data = ['file_id' => $file_id];
        return $this->baseRepository->run($query, $data);
    }

    /**
     * @return int
     */
    public function getNewCount(): int {
        $date = date('Y-m-d H:i:s', strtotime('-1 week'));
        $query = "SELECT COUNT(*) as count FROM trip_files
                WHERE created_at >= '$date'";
        return $this->baseRepository->fetch($query)['count'];
    }

    // TODO: Create
}
