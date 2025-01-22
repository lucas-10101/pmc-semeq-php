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
class SellDAO
{

    /**
     * Insert the address, sale, products and then update sale total field based on products.
     * @param \models\Sell $sell
     * @return bool true if insert is ok
     */
    public function save($sell)
    {
        $connection = null;
        try {
            $connection = DatabaseConnection::getConnection();

            $connection->beginTransaction();

            $stm = $connection->prepare(<<<SQL
                INSERT INTO "Address" ("postal_code", "street_number", "street", "district", "state", "city", "complement") 
                VALUES (:postal_code, :street_number, :street, :district, :state, :city, :complement)
            SQL);

            $stm->bindValue("postal_code", $sell->postal_code);
            $stm->bindValue("street_number", $sell->street_number, PDO::PARAM_INT);
            $stm->bindValue("street", $sell->street);
            $stm->bindValue("district", $sell->district);
            $stm->bindValue("state", $sell->state);
            $stm->bindValue("city", $sell->city);
            $stm->bindValue("complement", $sell->complement);

            if (!$stm->execute()) {
                $connection->rollBack();
                return false;
            }

            $stm = $connection->prepare(<<<SQL
                INSERT INTO "Sales" ("sale_date", "client_id", "seller_id", "total", "address_id") 
                VALUES (TO_DATE(:sale_date, 'YYYY-MM-DD'), :client_id, :seller_id, 0, (SELECT MAX("id") FROM "Address"))
            SQL);

            $stm->bindValue("sale_date", $sell->sale_date);
            $stm->bindValue("client_id", $sell->client_id, PDO::PARAM_INT);
            $stm->bindValue("seller_id", $sell->seller_id, PDO::PARAM_INT);

            if (!$stm->execute()) {
                $connection->rollBack();
                return false;
            }

            $stm = $connection->query('SELECT MAX("id") FROM "Sales"');

            if (!$stm->execute()) {
                $connection->rollBack();
                return false;
            }

            $sale_id = intval($stm->fetchColumn());
            $productsQuery = 'INSERT ALL ';
            foreach ($sell->items as $item => $saleProduct) {

                $productId = intval($saleProduct->productId);
                $quantity = intval($saleProduct->quantity);

                $productsQuery .= "INTO \"Sale_Products\" (\"sale_id\", \"product_id\", \"quantity\") VALUES ($sale_id, $productId, $quantity) ";
            }

            $productsQuery .= " SELECT 1 FROM DUAL";

            echo $productsQuery;

            $stm = $connection->prepare($productsQuery);

            if (!$stm->execute()) {
                $connection->rollBack();
                return false;
            }

            $stm = $connection->query(<<<SQL
                UPDATE "Sales" SET "Sales"."total" = (
                    SELECT 
                        SUM(SP."quantity" * P."price")
                    FROM
                        "Sales" S
                        INNER JOIN "Sale_Products" SP ON SP."sale_id" = S."id"
                        INNER JOIN "Products" P ON P."id" = SP."product_id"
                ) WHERE "Sales"."id" = (SELECT MAX("id") FROM "Sales")
            SQL);

            if (!$stm->execute()) {
                $connection->rollBack();
                return false;
            }

            $connection->commit();

            return true;
        } catch (\Exception $e) {

            if ($connection != null) {
                $connection->rollBack();
            }

            header("Location: /error.php");
            exit;
        }
    }
}
