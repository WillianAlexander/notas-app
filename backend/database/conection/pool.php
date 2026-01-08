<?php
$connectionFile = __DIR__ . "/conection.php";
if (!file_exists($connectionFile)) {
    throw new Exception("Connection file not found: " . $connectionFile);
}
require_once $connectionFile;

class Pool
{
    private static $instance = null;
    private static $connections = [];
    private static $available = [];
    private static $max_connections = 10;
    private static $current_connections = 0;

    private function __construct() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getConnection()
    {
        // Si hay conexiones disponibles, devolver una
        if (!empty(self::$available)) {
            return array_pop(self::$available);
        }

        // Si no hemos alcanzado el límite, crear una nueva
        if (self::$current_connections < self::$max_connections) {
            try {
                $connectionObject = new Connection();
                $conn = $connectionObject->getConnection();
                self::$connections[] = $conn;
                self::$current_connections++;
                return $conn;
            } catch (Exception $e) {
                error_log("Pool connection error: " . $e->getMessage());
                throw $e;
            }
        }

        // Si alcanzó el límite, esperar o lanzar error
        throw new Exception("Maximum pool connections reached");
    }

    public static function releaseConnection($connection)
    {
        if (in_array($connection, self::$connections, true)) {
            self::$available[] = $connection;
        }
    }

    public static function closeAllConnections()
    {
        self::$connections = [];
        self::$available = [];
        self::$current_connections = 0;
    }

    public static function getPoolStatus()
    {
        return [
            'total' => self::$current_connections,
            'available' => count(self::$available),
            'in_use' => self::$current_connections - count(self::$available),
        ];
    }
}
?>