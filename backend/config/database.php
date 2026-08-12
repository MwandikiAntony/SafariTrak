<?php

class MySQLiPDOFallback {
    private mysqli $mysqli;

    public function __construct(mysqli $mysqli) {
        $this->mysqli = $mysqli;
    }

    public function prepare(string $sql): MySQLiStatement {
        return new MySQLiStatement($this->mysqli, $sql);
    }

    public function lastInsertId(): string {
        return (string) $this->mysqli->insert_id;
    }
}

class MySQLiStatement {
    private mysqli $mysqli;
    private string $sql;
    private mixed $result = null;

    public function __construct(mysqli $mysqli, string $sql) {
        $this->mysqli = $mysqli;
        $this->sql = $sql;
    }

    private function interpolateQuery(array $params): string {
        $sql = $this->sql;

        foreach ($params as $param) {
            if ($param === null) {
                $replacement = 'NULL';
            } elseif (is_int($param) || is_float($param)) {
                $replacement = (string) $param;
            } else {
                $replacement = "'" . $this->mysqli->real_escape_string((string) $param) . "'";
            }
            $sql = preg_replace('/\?/', $replacement, $sql, 1);
        }

        return $sql;
    }

    public function execute(array $params = []): bool {
        $query = $this->interpolateQuery($params);
        $this->result = $this->mysqli->query($query);
        if ($this->result === false) {
            throw new RuntimeException($this->mysqli->error . ' [SQL: ' . $query . ']');
        }
        return true;
    }

    public function fetch(): array|false {
        if ($this->result instanceof mysqli_result) {
            return $this->result->fetch_assoc();
        }
        return false;
    }

    public function fetchAll(): array {
        if ($this->result instanceof mysqli_result) {
            return $this->result->fetch_all(MYSQLI_ASSOC);
        }
        return [];
    }
}

function safaritrak_db(): object {
    static $db = null;

    if ($db !== null) {
        return $db;
    }

    $host = getenv('SAFARITRAK_DB_HOST') ?: '127.0.0.1';
    $name = getenv('SAFARITRAK_DB_NAME') ?: 'safaritrak';
    $user = getenv('SAFARITRAK_DB_USER') ?: 'root';
    $pass = getenv('SAFARITRAK_DB_PASS') ?: '';

    $dsn = "mysql:host={$host};dbname={$name};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
        $db = $pdo;
    } catch (PDOException $e) {
        if (!class_exists('mysqli')) {
            throw $e;
        }
        $mysqli = new mysqli($host, $user, $pass, $name);
        if ($mysqli->connect_error) {
            throw new RuntimeException('MySQL connection failed: ' . $mysqli->connect_error);
        }
        $mysqli->set_charset('utf8mb4');
        $db = new MySQLiPDOFallback($mysqli);
    }

    return $db;
}
