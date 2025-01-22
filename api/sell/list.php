<?php

require_once __DIR__ . "/../../autoload.php";
use classes\AuthenticationHandler;
use classes\SessionManager;
AuthenticationHandler::verify();

header("Content-Type: application/json");

// If the user is an client, then get client, otherwise use global listing
$user = SessionManager::getUser();
$person = SessionManager::getUserPerson();
$clientId = $user->role == "CLIENT" ? $person->id : -1;

$sellDAO = new \dao\SaleDAO();

echo json_encode($sellDAO->listAll($clientId));