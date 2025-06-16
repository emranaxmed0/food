<?php
require_once("includes/db_connect.php");

$response = ["success" => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $foodId = $_POST["foodId"];
    $customerName = $_POST["customerName"];
    $phoneNumber = $_POST["phoneNumber"];
    $quantity = (int)$_POST["quantity"];

    $result = $conn->query("SELECT price FROM food WHERE foodId = $foodId");
    $row = $result->fetch_assoc();
    $price = $row["price"];
    $totalAmount = $price * $quantity;

    $stmt = $conn->prepare("INSERT INTO orders (foodId, customerName, phoneNumber, quantity, totalAmount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issid", $foodId, $customerName, $phoneNumber, $quantity, $totalAmount);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["orderId"] = $stmt->insert_id;
    } else {
        $response["message"] = "Failed to save order.";
    }
}

header("Content-Type: application/json");
echo json_encode($response);
