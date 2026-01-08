<?php
require_once __DIR__ . "/../database/conection/conection.php";

class Auth
{
    private $conn;
    private $table_name = "usuario";

    public function __construct()
    {
        $this->conn = Pool::getConnection();
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email AND activo = 1 LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return null;
    }
}
?>