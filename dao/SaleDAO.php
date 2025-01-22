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
class SaleDAO
{

    /**
     * Insert the address, sale, products and then update sale total field based on products.
     * @param \models\Sale $sell
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
                    WHERE S."id" = "Sales"."id"
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

    /**
     * List all sales, can be filtered by client
     * @param int $clientId Id of client
     * @return array|false
     */
    public function listAll($clientId = -1)
    {
        try {

            $connection = DatabaseConnection::getConnection();

            $stm = $connection->prepare(<<<SQL
                SELECT
                    SALE."id" AS "saleId",
                    SALE."total",
                    TO_CHAR(SALE."sale_date", 'DD/MM/YYYY') AS "saleDate",

                    SALE."client_id",
                    CLIENT."name" AS "clientName",

                    SALE."seller_id",
                    SELLER."name" AS "sellerName",

                    SALE."address_id",
                    ADDR."postal_code" AS "addressPostalCode",
                    ADDR."street_number" AS "addressStreetNumber",
                    ADDR."street" AS "addressStreet",
                    ADDR."district" AS "addressDistrict",
                    ADDR."city" AS "addressCity",
                    ADDR."state" AS "addressState",
                    ADDR."complement" AS "addressComplement"
                FROM
                    "Sales" SALE
                    INNER JOIN "Clients" CLIENT ON CLIENT."id" = SALE."client_id"
                    INNER JOIN "Sellers" SELLER ON SELLER."id" = SALE."seller_id"
                    INNER JOIN "Address" ADDR ON ADDR."id" = SALE."address_id"
                    
                WHERE 
                    (SALE."client_id" = :client_id OR :client_id = -1)
                ORDER BY SALE."id" DESC
            SQL);

            $stm->bindValue("client_id", $clientId, PDO::PARAM_INT);

            if (!$stm->execute()) {
                return false;
            }

            $saleList = $stm->fetchAll(PDO::FETCH_OBJ);

            $idList = array();
            foreach ($saleList as $sale) {
                array_push($idList, $sale->saleId);
                $sale->items = [];
            }

            $inList = implode(",", $idList);

            $stm = $connection->prepare(<<<SQL
                SELECT
                    SP."sale_id" AS "saleId",
                    SP."product_id" AS "productId",
                    SP."quantity",
                    P."price" AS "unitPrice",
                    P."name" AS "productName"
                FROM
                    "Sale_Products" SP
                    INNER JOIN "Products" P ON P."id" = SP."product_id"
                WHERE SP."sale_id" IN ($inList)
                ORDER BY SP."sale_id" DESC
            SQL);

            if (!$stm->execute()) {
                return false;
            }

            $productList = $stm->fetchAll(PDO::FETCH_OBJ);

            /**
             * The results are ordered, this is important to the next procedure
             */
            foreach ($saleList as $sale) {
                foreach ($productList as $product) {

                    $product->unitPrice = floatval(str_replace(",", ".", str_replace(".", "", $product->unitPrice)));

                    if ($product->saleId == $sale->saleId) {
                        array_push($sale->items, array_shift($productList));
                    }
                }
            }

            return $saleList;
        } catch (\Exception $e) {
            header("Location: /error.php");
            exit;
        }
    }
}
