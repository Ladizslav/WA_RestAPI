<?php

class DBC
{
    public static $SERVER_IP = "localhost";
    public static $USER = "root";
    public static $PASSWORD = "";
    public static $DATABASE = "blog";

    private static $connection = null;

    protected function __construct()
    {
    }

    public static function getConnection(): ?PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    'mysql:host=' . self::$SERVER_IP . ';dbname=' . self::$DATABASE,
                    self::$USER,
                    self::$PASSWORD,
                    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
                );
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
        }
        return self::$connection;
    }

    public static function closeConnection()
    {
        if (self::$connection !== null) {
            self::$connection = null; 
        }
    }
}
