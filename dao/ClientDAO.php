<?php

namespace dao;

use classes\DatabaseConnection;
use PDO;

class ClientDAO
{

    /**
     * Find client by user id
     * @param mixed $userId
     * @return bool|\models\Client
     */
    public function findByUserId($userId)
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT "id", "name" FROM "Clients" WHERE "user_id" = :user_id
            SQL);

            $stm->bindValue("user_id", $userId);

            $stm->execute();

            $result = $stm->fetchObject();

            return $result;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }


    /**
     * Return all clients (non-paged)
     * @return array
     */
    public function findAll()
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT "id", "name" FROM "Clients"
            SQL);

            $stm->execute();

            $data = $stm->fetchAll(\PDO::FETCH_OBJ);

            return $data;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }


    /**
     * Return all clients by name (non-paged)
     * @param string $name
     * @return array
     */
    public function findAllByName($name)
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT "id", "name", "user_id" FROM "Clients" WHERE UPPER("name") LIKE '%' || UPPER(:name) || '%'
            SQL);

            $stm->bindValue("name", $name);
            $stm->execute();

            $data = $stm->fetchAll(\PDO::FETCH_OBJ);

            return $data;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }

}