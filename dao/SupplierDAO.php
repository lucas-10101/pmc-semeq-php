<?php

namespace dao;

use classes\DatabaseConnection;

/**
 * 
 * Meu PHP estava considerando numeros como strings, provavelmente o php estava em en-US e meu banco em pt-BR, pelo tempo apenas apliquei a formatação necessária.
 * 
 * Pode ocorrer problemas de formatação caso a configuração do DB de teste estaja diferente.
 */
class SupplierDAO
{

    /**
     * Find supplier by user id
     * @param mixed $supplierId
     * @return bool|\models\Supplier
     */
    public function findById($supplierId)
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT "id", "name" FROM "Suppliers" WHERE "id" = :supplier_id
            SQL);

            $stm->bindValue("supplier_id", $supplierId);

            $stm->execute();

            $product = $stm->fetchObject();

            return $product;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }

    /**
     * Summary of findAll
     * @return array
     */
    public function findAll()
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
            SELECT "id", "name" FROM "Suppliers"
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
     * Save or update the supplier based on entity "id" field
     * @param \models\Supplier $supplier
     * @return int
     */
    public function save($supplier)
    {
        try {

            $id = is_int($supplier->id) ? intval($supplier->id) : 0;
            $name = "$supplier->name";

            $updating = is_int($id) && $id > 0;

            $connection = DatabaseConnection::getConnection();

            $connection->beginTransaction();
            if ($updating) {
                $stm = $connection->prepare(<<<SQL
                    UPDATE "Suppliers" SET "name" = :name WHERE "id" = :id
                SQL);


                $stm->bindValue("id", $id);
            } else {
                $stm = $connection->prepare(<<<SQL
                    INSERT INTO "Suppliers" ("name") VALUES (:name)
                SQL);
            }

            $stm->bindValue("name", $name);

            if ($stm->execute()) {

                $connection->commit();

                if ($updating) {
                    $stm = $connection->prepare(<<<SQL
                        SELECT MAX("id") FROM "Suppliers"
                    SQL);

                    $stm->execute();
                    $result = $stm->fetchColumn();

                    return $result != false ? $result : -1;

                }
                return $id;
            }
            return -1;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }

    }

    /**
     * Delete the product
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        try {
            $connection = DatabaseConnection::getConnection();

            $stm = $connection->prepare(<<<SQL
                DELETE FROM "Suppliers" WHERE "id" = :id
            SQL);

            $stm->bindValue("id", $id);

            $stm->execute();
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }
}