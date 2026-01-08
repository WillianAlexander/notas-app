<?php
// Cargar .env simple si existe (ubicado en la raíz del proyecto)
$envFile = realpath(__DIR__ . '/../../') . DIRECTORY_SEPARATOR . '.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) continue;
        [$k, $v] = array_map('trim', explode('=', $line, 2) + [1 => '']);
        if ($k !== '') {
            if (!getenv($k)) putenv("$k=$v");
            if (!isset($_ENV[$k])) $_ENV[$k] = $v;
        }
    }
}

// Usar getenv() con valores por defecto
$host    = getenv('DB_HOST') ?: '127.0.0.1';
$port    = getenv('DB_PORT') ?: 3306;
$db_name = getenv('DB_NAME') ?: 'notas_app';
$user    = getenv('DB_USER') ?: 'root';
$pass    = getenv('DB_PASSWORD') ?: '';

class Connection
{
    private $conn;
    public function getConnection()
    {
        $this->conn = null;
        try {
            global $host, $port, $db_name, $user, $pass;

            $this->conn = new PDO(
                "mysql:host={$host};port={$port};dbname={$db_name};charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $exception) {
            error_log("Connection error: " . $exception->getMessage());
            throw new Exception("Database connection failed");
        }
        return $this->conn;
    }
}