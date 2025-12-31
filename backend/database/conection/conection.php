<?php
class Connection
{
    public $conn;
    public function getConnection()
    {

        $this->conn = null;
        try {
            $host = $_ENV["DB_HOST"];
            $port = $_ENV["DB_PORT"];
            $db_name = $_ENV["DB_NAME"];
            $user = $_ENV["DB_USER"];
            $pass = $_ENV["DB_PASSWORD"];

            $this->conn = new PDO(
                "mysql:host=" . $host . ";port=" . $port . ";dbname=" . $db_name,
                $user,
                $pass
            );
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        return $this->conn;
    }
}