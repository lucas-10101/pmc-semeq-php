<?php

require_once __DIR__ . "/../../autoload.php";
use classes\AuthenticationHandler;
AuthenticationHandler::verify();

header("Content-Type: application/json");
$return = new stdClass();
$return->results = array();

$productName = filter_input(INPUT_GET, "product_name", FILTER_SANITIZE_STRING);

$clientDAO = new dao\ProductDAO();
$return->results = $clientDAO->findAllByName("$productName");

die(json_encode($return));
