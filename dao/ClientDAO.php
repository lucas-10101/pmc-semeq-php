<?php

namespace dao;

use classes\DatabaseConnection;

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

            return $stm->fetchObject();
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }
}