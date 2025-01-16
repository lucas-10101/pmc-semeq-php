<?php

namespace dao;

use classes\DatabaseConnection;

class ProductDAO
{

    /**
     * Find product by user id
     * @param mixed $productId
     * @return bool|\models\Product
     */
    public function findById($productId)
    {

        $connection = DatabaseConnection::getConnection();
        $stm = $connection->prepare(<<<SQL
            SELECT "id", "name", "price" FROM "Products" WHERE "id" = :product_id
        SQL);

        $stm->bindValue("product_id", $productId);

        $stm->execute();

        $product = $stm->fetchObject();
        $product->price = floatval(str_replace(",", ".", str_replace(".", "", $product->price)));

        return $product;
    }

    /**
     * Summary of findAll
     * @return array
     */
    public function findAll()
    {
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
    }

    /**
     * Save the product in database
     * @param \models\Product $product
     * @return void
     */
    public function save($id, $name, $price)
    {

    }
}