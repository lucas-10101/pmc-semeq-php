<?php

namespace dao;

error_reporting(E_ALL);

use classes\DatabaseConnection;

class SellerDAO
{

    public function findByUserId($userId)
    {

        $connection = DatabaseConnection::getConnection();
        $stm = $connection->prepare(<<<SQL
            SELECT id, name FROM Sellers WHERE user_id = :user_id
        SQL);

        $stm->bindValue("user_id", $userId);

        $stm->execute();

        return $stm->fetchObject();
    }
}