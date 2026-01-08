<?php

require_once __DIR__ . "/../../database/conection/pool.php";

class RegisterUser
{
    private $db;
    private $table_name = "usuario";

    public function __construct()
    {
        $this->db = Pool::getConnection();
    }

    public function handleRegister()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            $result = $this->register(
                $data['nombre'] ?? '',
                $data['username'] ?? '',
                $data['email'] ?? '',
                $data['password'] ?? ''
            );

            if ($result['success']) {
                http_response_code(201);
            } else {
                http_response_code(400);
            }

            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error interno del servidor"]);
        } finally {
            Pool::releaseConnection($this->db);
        }
    }

    private function register($nombre, $username, $email, $password)
    {
        try {
            if (empty($nombre) || empty($username) || empty($email) || empty($password)) {
                throw new Exception("Nombre, username, email y password son requeridos");
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email inválido");
            }

            if (strlen($password) < 6) {
                throw new Exception("Password debe tener mínimo 6 caracteres");
            }

            // Check if username already exists
            $query = "SELECT id_usuario FROM " . $this->table_name . " WHERE username = :username LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("Username ya existe");
            }

            // Verificar email duplicado
            $query = "SELECT id_usuario FROM " . $this->table_name . " WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->execute();

            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("Email ya registrado");
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            // Insert new user
            $query = "INSERT INTO " . $this->table_name . " (nombre, username, email, password_hash, activo) VALUES (:nombre, :username, :email, :password, 1)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":password", $hashed_password, PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Error al registrar usuario");
            }

            return [
                "success" => true,
                "message" => "Usuario registrado exitosamente"
            ];
        } catch (Exception $e) {
            error_log("Register error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}
?>