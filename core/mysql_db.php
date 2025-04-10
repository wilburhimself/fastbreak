<?php

namespace Core;

use mysqli;
use mysqli_result;

class MysqlDB
{
    private ?mysqli $conn;
    public static int $queries_count = 0;

    public function __construct()
    {
        $this->conn = new mysqli(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset("utf8");
    }

    public function select(array $p): MysqlResult
    {
        $query = "SELECT ";

        $query .= $p['select'] ?? "*";
        $query .= " FROM " . ($p['from'] ?? $p['table']);

        if (isset($p['where'])) {
            $query .= " WHERE " . $p['where'];
        } elseif (isset($p["key"]) and !isset($p["quantity"])) {
            $query .= " WHERE id = " . $p["key"];
        }

        if (isset($p['or_where'])) {
            $query .= " OR WHERE " . $p['where'];
        } elseif (isset($p["key"]) and !isset($p["quantity"])) {
            $query .= " OR WHERE id = " . $p["key"];
        }

        if (isset($p["order"])) {
            $query .= " ORDER BY " . $p["order"];
        } elseif (isset($p['arrange'])) {
            $query .= " ORDER BY id " . $p['arrange'];
        }

        if (isset($p['limit'])) {
            $query .= ' LIMIT ' . $p['limit'];
        }

        if (isset($p['page'])) {
            $pages = ceil($this->query($query)->size() / ROWSPERPAGE);
            if ($_GET['page'] > $pages) {
                $_GET['page'] = $pages;
            }
            if ($_GET['page'] < 1) {
                $_GET['page'] = 1;
            }
            $_SESSION['pages'] = $pages;
            $query .= " LIMIT " . (($_GET['page'] - 1) * ROWSPERPAGE) . ", " . ROWSPERPAGE;
        }
        if (isset($p['debug'])) {
            print $query;
        }
        return $this->query($query);
    }

    public function query(string $sql): MysqlResult
    {
        $result = $this->conn->query($sql);
        if ($result === false) {
            exit('Error en el query: ' . $this->conn->error);
        }
        self::$queries_count += 1;
        return new MysqlResult($this->conn, $result);
    }

    public function noQuery(string $sql): bool
    {
        return $this->conn->query($sql) === true;
    }

    public function insert(array $dataNames, array $dataValues, string $tableName): bool
    {
        $sqlNames = "INSERT INTO " . $tableName . " (" . implode(", ", $dataNames) . ") VALUES (";
        $placeholders = implode(", ", array_fill(0, count($dataValues), "?"));
        $sql = $sqlNames . $placeholders . ")";

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $this->conn->error);
        }

        $types = str_repeat("s", count($dataValues));
        $stmt->bind_param($types, ...$dataValues);

        return $stmt->execute();
    }

    public function update(array $dataNames, array $dataValues, string $tableName, string $condition): bool
    {
        $sql = "UPDATE " . $tableName . " SET ";
        $sets = [];
        foreach ($dataNames as $name) {
            $sets[] = $name . " = ?";
        }
        $sql .= implode(", ", $sets) . " " . $condition;

        $stmt = $this->conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $this->conn->error);
        }

        $types = str_repeat("s", count($dataValues));
        $stmt->bind_param($types, ...$dataValues);

        return $stmt->execute();
    }

    public function insertId(): int
    {
        return $this->conn->insert_id;
    }
}

class MysqlResult
{
    private mysqli $conn;
    private mysqli_result $result;

    public function __construct(mysqli $db, mysqli_result $result)
    {
        $this->conn = $db;
        $this->result = $result;
    }

    public function fetch(): ?array
    {
        $row = $this->result->fetch_array(MYSQLI_ASSOC);
        if ($row) {
            return $row;
        } elseif ($this->size() > 0) {
            $this->result->data_seek(0);
            return null;
        } else {
            return null;
        }
    }

    public function fetch_single_object(): ?object
    {
        $row = $this->result->fetch_object();
        if ($row) {
            return $row;
        } elseif ($this->size() > 0) {
            $this->result->data_seek(0);
            return null;
        } else {
            return null;
        }
    }

    public function size(): int
    {
        return $this->result->num_rows;
    }

    public function isBlank(): bool
    {
        return $this->size() == 0;
    }

    public function getPages(): int
    {
        return ceil($this->size() / ROWSPERPAGE);
    }

    public function fetch_array(): array
    {
        $data_array = [];
        while ($r = $this->result->fetch_array()) {
            $data_array[] = $r;
        }
        return $data_array;
    }

    public function fetch_object(): array
    {
        $data_array = [];
        while ($r = $this->result->fetch_object()) {
            $data_array[] = $r;
        }
        return $data_array;
    }
}
?>