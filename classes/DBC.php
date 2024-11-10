<?php

class DBC
{
    public static $SERVER_IP = "localhost";
    public static $USER = "root";
    public static $PASSWORD = "";
    public static $DATABASE = "blog";

    private static $connection = null;

    protected function __construct() {}

    public static function getConnection(): ?PDO
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    'mysql:host=' . self::$SERVER_IP . ';dbname=' . self::$DATABASE,
                    self::$USER,
                    self::$PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Povolení vyhazování chyb
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Výchozí způsob načítání výsledků
                        PDO::ATTR_EMULATE_PREPARES => false, // Reálné přípravy dotazů
                    ]
                );
            } catch (PDOException $e) {
                error_log("DB Connection failed: " . $e->getMessage());
                throw new PDOException("Database connection failed. Please check your configuration.");
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

