<?php
require_once __DIR__ . "/../../database/conection/pool.php";

class LoginUser
{
    private $db;
    private $table_name = "usuario";

    public function __construct()
    {
        $this->db = Pool::getConnection();
    }

    public function handleLogin()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);

            $result = $this->login(
                $data['username'] ?? '',
                $data['password'] ?? ''
            );

            if ($result['success']) {
                http_response_code(200);
            } else {
                http_response_code(401);
            }

            echo json_encode($result);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["success" => false, "message" => "Error interno del servidor"]);
        } finally {
            if ($this->db) {
                Pool::releaseConnection($this->db);
            }
        }
    }

    private function login($username, $password)
    {
        try {
            if (empty($username) || empty($password)) {
                throw new Exception("Username y password son requeridos");
            }

            $query = "SELECT id_usuario, nombre, username, email FROM " . $this->table_name . " WHERE username = :username AND activo = 1";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                throw new Exception("Usuario no encontrado");
            }

            // Obtener hash de contraseña
            $query = "SELECT password_hash FROM " . $this->table_name . " WHERE id_usuario = :id";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(":id", $user['id_usuario'], PDO::PARAM_INT);
            $stmt->execute();
            $passwordData = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!password_verify($password, $passwordData['password_hash'])) {
                throw new Exception("Contraseña incorrecta");
            }

            return [
                "success" => true,
                "message" => "Login exitoso",
                "user" => $user
            ];
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            return [
                "success" => false,
                "message" => $e->getMessage()
            ];
        }
    }
}
