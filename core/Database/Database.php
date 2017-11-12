<?php

namespace Core\Database;

use Core\Config;

/**
 * Instance of class handles Database connection,
 * binds parameters and executes SQL queries
 * @package Core
 * @author Martin Brom
 */
class Database
{
    /** @var \PDO Database connection */
    private $pdo;

    /** @var Config Instance containing configuration data */
    private $config;

    /** @var \PDOStatement Query results */
    private $statement;

    /**
     * Creates new Database instance and injects Config instance
     * @param Config $config Instance containing configuration data
     */
    public function __construct(Config $config) {
        $this->config = $config;
        $this->initConnection();
    }

    /**
     * Initializes database connection from database config values
     */
    public function initConnection() {
        $dsn = 'mysql:dbname=' . $this->config->get('db_name') . ';host=' . $this->config->get('db_host');
        $username = $this->config->get('db_username');
        $password = $this->config->get('db_password');
        $settings = [
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => false
        ];

        $this->pdo = new \PDO($dsn, $username, $password, $settings);
    }

    /**
     * Executes given sql query with given data
     * @param $query string SQL query with placeholders for binding data
     * @param array $data Data to bind with query
     * @return self Return itself for chaining functions
     */
    public function query(string $query, array $data = []): self {
        $this->statement = $this->pdo->prepare($query);
        $this->statement->execute($data);
        return $this;
    }

    /**
     * Get first row from result
     * @return mixed Associative array with first row from result, false on failure
     */
    public function fetch() {
        return $this->statement->fetch();
    }

    /**
     * Get all rows from result
     * @return array Associative array with all rows from result
     */
    public function fetchAll(): array {
        return $this->statement->fetchAll();
    }

    /**
     * Returns the id of last row affected
     * @return string ID of last row affected
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }
}