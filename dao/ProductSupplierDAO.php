<?php

namespace dao;

use classes\DatabaseConnection;

/**
 * 
 * Meu PHP estava considerando numeros como strings, provavelmente o php estava em en-US e meu banco em pt-BR, pelo tempo apenas apliquei a formatação necessária.
 * 
 * Pode ocorrer problemas de formatação caso a configuração do DB de teste estaja diferente.
 */
class ProductSupplierDAO
{

    /**
     * Summary of findAll
     * @return array
     */
    public function findAll()
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT
                    PS."product_id",
                    P."name" AS "product_name",
                    PS."supplier_id",
                    S."name" AS "supplier_name"
                FROM 
                    "Product_Suppliers" PS
                    INNER JOIN "Products" P ON P."id" = PS."product_id"
                    INNER JOIN "Suppliers" S ON S."id" = PS."supplier_id"
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
     * @param \models\ProductSupplier $supplier
     * @return int
     */
    public function save($supplier)
    {
        try {

            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
            SQL);

            $stm->execute();
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