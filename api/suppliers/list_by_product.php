<?php

require_once __DIR__ . "/../../autoload.php";
use classes\AuthenticationHandler;
AuthenticationHandler::verify();

header("Content-Type: application/json");

if (!isset($_GET['product_id'])) {
    die(json_encode(array()));
}

$productId = filter_input(INPUT_GET, "product_id", FILTER_SANITIZE_NUMBER_INT);

$clientDAO = new dao\ProductSupplierDAO();
$return = $clientDAO->findSuppliersByProductId("$productId");

die(json_encode($return));
