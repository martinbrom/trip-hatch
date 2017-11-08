<?php

namespace Core\Database;

/**
 * Instance of class provides a middle layer
 * between individual Repositories and the Database
 * Unifies ways to access data for each individual Repository
 * @package Core
 * @author Martin Brom
 */
class Repository
{
    /** @var Database Instance of connection to the database */
    private $database;

    /**
     * Creates new Repository instance and injects Database instance
     * @param Database $database Instance of connection to the database
     */
    public function __construct(\Core\Database\Database $database) {
        $this->database = $database;
    }

    /**
     * Get all rows from result for given query with data
     * @param string $query SQL query with placeholders for binding data
     * @param array $data Data to bind with query
     * @return array Associative array with all rows from result
     */
    public function fetchAll(string $query, array $data = []): array {
        return $this->database->query($query, $data)->fetchAll();
    }

    /**
     * Get first row from result for given query with data
     * @param string $query SQL query with placeholders for binding data
     * @param array $data Data to bind with query
     * @return array Associative array with first row from result
     */
    public function fetch(string $query, array $data = []): array {
        return $this->database->query($query, $data)->fetch();
    }
}