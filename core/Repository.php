<?php

namespace Core;

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

    public function delete($table, $conditions = []) {
        $sql = 'DELETE FROM ' . $table;

        if (!empty($conditions)) {
            $sql .= ' WHERE ';

            foreach ($conditions as $condition) {
                $sql .= $condition . ', ';
            }

            $sql = rtrim($sql, ', ');
        }

        $this->database
            ->delete($sql);
    }

    // TODO: Offset and where clauses
    public function select($table, $limit = NULL) {
        $sql = 'SELECT * FROM ' . $table;

        if ($limit != NULL)
            $sql .= ' LIMIT ' . $limit;

        return $this->database
            ->select($sql);
    }
}