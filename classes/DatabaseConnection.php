<?php

namespace classes;

USE PDO;

class DatabaseConnection
{
    private static $connection = null;
    private const PROVIDER = "oci";
    private const HOST = "127.0.0.1";
    private const PORT = "1521";
    private const USERNAME = "app";
    private const PASSWORD = "app";
    private const DATABASE = "PMC";

    public function __construct()
    {
        if (DatabaseConnection::$connection == null) {
            $dsn = self::PROVIDER . ":host=" . self::HOST . ";port=" . self::PORT . ";dbname=" . self::DATABASE;
            DatabaseConnection::$connection = new PDO($dsn, self::USERNAME, self::PASSWORD);
        }
    }

    public function getConnection()
    {
        return DatabaseConnection::$connection;
    }
}

