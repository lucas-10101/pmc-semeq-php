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
                SELECT "id", "email", "password", "role" FROM "Users" WHERE "email" = :email
            SQL);

            $stm->bindValue("email", $email);

            if (!$stm->execute()) {
                return false;
            }

            $foundUser = $stm->fetchObject();

            if (!password_verify($password, $foundUser->password)) {
                return false;
            }

            unset($foundUser->password);

            return $foundUser;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }
}