<?php

namespace classes;

use PDO;

class DatabaseConnection
{
    private static $connection = null;

    private static $provider = "oracle";

    private static $host = "127.0.0.1";

    private static $port = 1521;

    private static $username = "PMC";

    /**
     * 
     * Por que não esta criptografado ou atribuido como variavel de ambiente ? Pois é só uma senha de testes.
     * @var string
     */
    private static $password = "PMC-APPLICATION-USER";

    private static $pdo_options = array(
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    public static function getConnection()
    {
        if (DatabaseConnection::$connection == null) {
            self::reconnect();
        }

        return DatabaseConnection::$connection;
    }

    public static function reconnect()
    {
        try {

            DatabaseConnection::$connection = null;
            switch (self::$provider) {
                case "oracle":
                default:
                    self::connectToOracleDatabase();
            }
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }

    private static function connectToOracleDatabase()
    {

        $host = DatabaseConnection::$host;
        $port = DatabaseConnection::$port;
        $serviceName = "XEPDB1";

        //$dsn = "oci:dbname=(DESCRIPTION=(ADDRESS_LIST=(ADDRESS=(PROTOCOL=TCP)(HOST=$host)(PORT=$port)))(CONNECT_DATA=(SID=ORCL)))";
        $dsn = "oci:dbname=$host:$port/$serviceName";

        DatabaseConnection::$connection = new PDO($dsn, self::$username, self::$password, self::$pdo_options);
    }
}

