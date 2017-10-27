<?php

namespace Core;

// TODO: Bulk insert and bulk load without n+1
// TODO: Refactor spaghetti into functions
class Repository
{
    private $database;

    public function __construct(\Core\Database $database) {
        $this->database = $database;
    }

    public function insert($table, $data) {
        $sql = 'INSERT INTO ' . $table;

        $parametersString = ' (';
        $valuesString = ' VALUES (';

        foreach ($data as $key => $value) {
            $parametersString .= $key . ', ';
            $valuesString .= ':' . $key . ', ';
        }

        $parametersString = rtrim($parametersString, ', ');
        $valuesString = rtrim($valuesString, ', ');

        $parametersString .= ')';
        $valuesString .= ')';

        $sql .= $parametersString . $valuesString;

        $this->database
            ->data($data)
            ->insert($sql);
    }

    public function update($table, $data, $conditions = []) {
        $sql = 'UPDATE ' . $table . ' SET ';

        foreach ($data as $key => $value) {
            $sql .= $key . ' = :' . $key . ', ';
        }

        $sql = rtrim($sql, ', ');

        if (!empty($conditions)) {
            $sql .= ' WHERE ';

            foreach ($conditions as $condition) {
                $sql .= $condition . ', ';
            }

            $sql = rtrim($sql, ', ');
        }

        $this->database
            ->data($data)
            ->update($sql);
    }

    /**
     * Delete data from given table based on given 'where' conditions
     * If 'where' condition is omitted, whole table will be deleted!
     * @param string $table Table name
     * @param array $where  Where conditions
     */
    public function delete($table, $where = []) {
        $sql = 'DELETE FROM ' . $table;

        if (!empty($where)) {
            $sql .= ' WHERE ';

            foreach ($where as $item) {
                $sql .= $item . ', ';
            }

            $sql = rtrim($sql, ', ');
        }

        $this->database
            ->delete($sql);
    }

    /**
     * Selects data from database based on given parameters
     * @param string $table  Table name
     * @param array $where   Where conditions
     * @param array $columns Columns to be selected
     * @param array $joins   Tables to join in the query as key and type of join as value
     * @param int $limit     Limit of results
     * @param int $offset    Offset of result set
     * @return array Result from database
     */
    public function select($table, $where = [], $columns = [], $joins = [], $limit = 0, $offset = 0) {
        $sql = 'SELECT ';

        // add selected columns to query string
        if (!empty($columns)) {
            foreach ($columns as $column) {
                $sql .= $column . ', ';
            }

            $sql = rtrim($sql, ', ');
        } else {
            $sql .= '*';    // if selected columns are empty select * (all columns)
        }

        $sql .= ' FROM ' . $table;

        // add joined tables to query string
        if (!empty($joins)) {
            foreach ($joins as $key => $value) {
                $sql .= ' ' . $value['type'] . ' JOIN ' . $key . ' ON ' . $value['condition'];
            }
        }

        // add where conditions to query string
        if (!empty($where)) {
            $sql .= ' WHERE ';

            foreach ($where as $item) {
                $sql .= $item . ', ';
            }

            $sql = rtrim($sql, ', ');
        }

        // add result limit to query string
        if ($limit != NULL) {
            $sql .= ' LIMIT ' . $limit;

            // add offset to query string, offset cannot be set without limit
            if ($offset != 0)
                $sql .= ' OFFSET ' . $offset;
        }

        // process
        return $this->database
            ->select($sql);
    }
}