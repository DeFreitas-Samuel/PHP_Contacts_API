<?php

declare(strict_types=1);

class Config
{

    private static string $dbEngine = "mysql";
    private static string $host = "localhost";
    private static string $database = "contacts";
    private static string $user = "root";
    private static string $password = "";
    private static $db = null;

    private static function getConnectionString()
    {
        return self::$dbEngine . ":host=" . self::$host . ";dbname=" . self::$database;
    }

    public static function getDB()
    {
        if (self::$db === null) {
            self::$db = new PDO(self::getConnectionString(), self::$user, self::$password);
        }
        return self::$db;
    }
}
