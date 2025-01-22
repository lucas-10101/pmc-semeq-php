<?php

require_once __DIR__ . "/../../autoload.php";
use classes\AuthenticationHandler;
AuthenticationHandler::verify();

header("Content-Type: application/json");
$return = new stdClass();
$return->results = array();

$clientName = filter_input(INPUT_GET, "client_name", FILTER_SANITIZE_STRING);

$clientDAO = new dao\ClientDAO();
$return->results = $clientDAO->findAllByName("$clientName");

die(json_encode($return));
