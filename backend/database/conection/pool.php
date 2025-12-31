<?php
require_once __DIR__ . "/../database/conection/conection.php";

class Pool
{
    private static $pool = null;
    public static function getConnection()
    {
        if (self::$pool === null) {
            self::$pool = new Connection();
            self::$pool->getConnection();
        }
        return self::$pool->getConnection();
    }
    public static function setPool(Connection $pool)
    {
        self::$pool = $pool;
    }
    public static function getPool()
    {
        return self::$pool;
    }
}
?>