<?php

require_once __DIR__ . "/../../autoload.php";
use classes\AuthenticationHandler;
use classes\SessionManager;
AuthenticationHandler::verify();

header("Content-Type: application/json");

$jsonData = json_decode(file_get_contents("php://input"), true);
$sanitized = new \models\Sale();

// sanitization of input
$sanitized->postal_code = filter_var($jsonData["postal_code"], FILTER_SANITIZE_STRING);
$sanitized->street_number = filter_var($jsonData["house_number"], FILTER_SANITIZE_NUMBER_INT) or die(http_response_code(400));
$sanitized->street = filter_var($jsonData["street"], FILTER_SANITIZE_STRING) or die(http_response_code(400));
$sanitized->district = filter_var($jsonData["district"], FILTER_SANITIZE_STRING) or die(http_response_code(400));
$sanitized->city = filter_var($jsonData["city"], FILTER_SANITIZE_STRING) or die(http_response_code(400));
$sanitized->state = filter_var($jsonData["state"], FILTER_SANITIZE_STRING) or die(http_response_code(400));
$sanitized->complement = filter_var($jsonData["complement"], FILTER_SANITIZE_STRING) or die(http_response_code(400));

$sanitized->client_id = filter_var($jsonData["client_id"], FILTER_SANITIZE_NUMBER_INT) or die(http_response_code(400));
$sanitized->sale_date = filter_var($jsonData["sell_date"], FILTER_SANITIZE_STRING) or die(http_response_code(400));
$sanitized->seller_id = SessionManager::getUserPerson()->id;

$sanitized->items = array();
if (isset($jsonData["items"]) && is_array($jsonData["items"])) {
    foreach ($jsonData["items"] as $index => $item) {

        $product = new stdClass();
        $product->productId = filter_var($item["product_id"], FILTER_SANITIZE_NUMBER_INT) or die(http_response_code(400));
        $product->quantity = filter_var($item["quantity"], FILTER_SANITIZE_NUMBER_INT) or die(http_response_code(400));

        array_push($sanitized->items, $product);
    }
} else {
    die(http_response_code(400));
}

// pattern validation
preg_match("/^[0-9]{5}-[0-9]{3}$/", $sanitized->postal_code, $matches) === 1 or die(http_response_code(400));
preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $sanitized->sale_date, $matches) === 1 or die(http_response_code(400));

$sellDAO = new \dao\SaleDAO();
$sellDAO->save($sanitized);

echo json_encode($sanitized);

