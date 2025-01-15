<?php

namespace dao;

error_reporting(E_ALL);

use classes\DatabaseConnection;

class UserDAO
{

    public function findByEmailAndPassword($email, $password)
    {

        $connection = DatabaseConnection::getConnection();
        $stm = $connection->prepare(<<<SQL
            SELECT id, email, role FROM Users WHERE email = :email AND password = :password
        SQL);

        $stm->bindValue("email", $email);
        $stm->bindValue("password", $password);

        $stm->execute();

        return $stm->fetchObject();
    }
}