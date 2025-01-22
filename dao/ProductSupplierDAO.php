<?php

namespace dao;

use classes\DatabaseConnection;
use PDO;

/**
 * 
 * Meu PHP estava considerando numeros como strings, provavelmente o php estava em en-US e meu banco em pt-BR, pelo tempo apenas apliquei a formatação necessária.
 * 
 * Pode ocorrer problemas de formatação caso a configuração do DB de teste estaja diferente.
 */
class ProductSupplierDAO
{


    /**
     * Find product supplier by id
     * @param int $productId
     * @param int $supplierId
     * @return bool|\models\ProductSupplier
     */
    public function findById($productId, $supplierId)
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
                WHERE
                    PS."product_id" = :product_id AND
                    PS."supplier_id" = :supplier_id
            SQL);

            $stm->bindValue("product_id", $productId);
            $stm->bindValue("supplierId", $supplierId);

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
     * @param \models\ProductSupplier $productSupplier
     * @return bool
     */
    public function save($productSupplier)
    {
        try {

            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT
                    COUNT(*) AS "count"
                FROM 
                    "Product_Suppliers" PS
                    INNER JOIN "Products" P ON P."id" = PS."product_id"
                    INNER JOIN "Suppliers" S ON S."id" = PS."supplier_id"
                WHERE
                    PS."product_id" = :product_id AND
                    PS."supplier_id" = :supplier_id
            SQL);

            $stm->bindValue("product_id", $productSupplier->product_id);
            $stm->bindValue("supplier_id", $productSupplier->supplier_id);

            if ($stm->execute()) {
                $countResult = $stm->fetchColumn(0);
                if ($countResult == 1) {
                    return true;
                }

                $stm = $connection->prepare(<<<SQL
                    INSERT INTO "Product_Suppliers" ("product_id", "supplier_id") VALUES(:product_id, :supplier_id)
                SQL);

                $stm->bindValue("product_id", $productSupplier->product_id);
                $stm->bindValue("supplier_id", $productSupplier->supplier_id);

                return $stm->execute() != false;
            }

            return false;

        } catch (\Exception $e) {

            //die(var_dump($e));
            header("Location: /error.php");
            exit;
        }

    }

    /**
     * Delete the product
     * @param \models\ProductSupplier $productSupplier
     * @return void
     */
    public function delete($productSupplier)
    {
        try {

            $connection = DatabaseConnection::getConnection();

            $stm = $connection->prepare(<<<SQL
                DELETE FROM "Product_Suppliers" WHERE "product_id" = :product_id AND "supplier_id" = :supplier_id 
            SQL);

            $stm->bindValue("product_id", $productSupplier->product_id);
            $stm->bindValue("supplier_id", $productSupplier->supplier_id);

            $stm->execute();
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }


    /**
     * Find suppliers by product id
     * @param int $productId
     * @return array |\models\ProductSupplier
     */
    public function findSuppliersByProductId($productId)
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
                WHERE
                    PS."product_id" = :product_id
            SQL);

            $stm->bindValue("product_id", $productId);
            $stm->execute();

            $suppliers = $stm->fetchAll(PDO::FETCH_OBJ);

            return $suppliers;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }
}