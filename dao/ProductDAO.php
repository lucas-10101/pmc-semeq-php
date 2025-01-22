<?php

namespace dao;

use classes\DatabaseConnection;

/**
 * 
 * Meu PHP estava considerando numeros como strings, provavelmente o php estava em en-US e meu banco em pt-BR, pelo tempo apenas apliquei a formatação necessária.
 * 
 * Pode ocorrer problemas de formatação caso a configuração do DB de teste estaja diferente.
 */
class ProductDAO
{

    /**
     * Find product by user id
     * @param mixed $productId
     * @return bool|\models\Product
     */
    public function findById($productId)
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT "id", "name", "price" FROM "Products" WHERE "id" = :product_id
            SQL);

            $stm->bindValue("product_id", $productId);

            $stm->execute();

            $product = $stm->fetchObject();
            $product->price = floatval(str_replace(",", ".", str_replace(".", "", $product->price)));

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
            SELECT "id", "name", "price" FROM "Products"
        SQL);

            $stm->execute();

            $data = $stm->fetchAll(\PDO::FETCH_OBJ);

            foreach ($data as $product) {
                $product->price = floatval(str_replace(",", ".", str_replace(".", "", $product->price)));
            }

            return $data;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }

    /**
     * Save or update the product based on entity "id" field
     * @param \models\Product $product
     * @return int
     */
    public function save($product)
    {
        try {

            $id = is_int($product->id) ? intval($product->id) : 0;
            $name = "$product->name";
            $price = is_float($product->price) ? floatval($product->price) : 0.00;

            $updating = is_int($id) && $id > 0;

            $connection = DatabaseConnection::getConnection();

            $connection->beginTransaction();
            if ($updating) {
                $stm = $connection->prepare(<<<SQL
                    UPDATE "Products" SET "name" = :name, "price" = :price WHERE "id" = :id
                SQL);


                $stm->bindValue("id", $id);
            } else {
                $stm = $connection->prepare(<<<SQL
                    INSERT INTO "Products" ("name", "price") VALUES (:name, :price)
                SQL);
            }

            $stm->bindValue("name", $name);
            $stm->bindValue("price", number_format($price, 2, ",", ""));

            if ($stm->execute()) {

                $connection->commit();

                if ($updating) {

                    $stm = $connection->prepare(<<<SQL
                        SELECT MAX("id") FROM "Products"
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
                DELETE FROM "Products" WHERE "id" = :id
            SQL);

            $stm->bindValue("id", $id);

            $stm->execute();
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }

    /**
     * Return all products by name (non-paged)
     * @param string $name
     * @return array
     */
    public function findAllByName($name)
    {
        try {
            $connection = DatabaseConnection::getConnection();
            $stm = $connection->prepare(<<<SQL
                SELECT "id", "name", "price" FROM "Products" WHERE UPPER("name") LIKE '%' || UPPER(:name) || '%'
            SQL);

            $stm->bindValue("name", $name);
            $stm->execute();

            $data = $stm->fetchAll(\PDO::FETCH_OBJ);

            foreach ($data as $product) {
                $product->price = floatval(str_replace(",", ".", str_replace(".", "", $product->price)));
            }

            return $data;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }
}