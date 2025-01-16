<?php

namespace dao;

use classes\DatabaseConnection;

class UserDAO
{

    /**
     * Find user by username and password
     * @param mixed $email
     * @param mixed $password
     * @return bool|\models\User
     */
    public function findByEmailAndPassword($email, $password)
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT "id", "email", "role" FROM "Users" WHERE "email" = :email AND "password" = :password
            SQL);

            $stm->bindValue("email", $email);
            $stm->bindValue("password", $password);

            $stm->execute();

            return $stm->fetchObject();
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }
}