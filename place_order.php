<?php
require_once("includes/db_connect.php");
require_once("mpesa/stk_push.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $foodId = intval($_POST['foodId']);
    $customerName = $conn->real_escape_string($_POST['customerName']);
    $phoneNumber = preg_replace('/\D/', '', $_POST['phoneNumber']);
    $quantity = intval($_POST['quantity']);

    // Get food price
    $result = $conn->query("SELECT price FROM food WHERE foodId = $foodId");
    if (!$result || $result->num_rows === 0) {
        die("Invalid food selection.");
    }
    $food = $result->fetch_assoc();
    $price = $food['price'];
    $totalAmount = $quantity * $price;

    // Save order
    $stmt = $conn->prepare("INSERT INTO orders (foodId, customerName, phoneNumber, quantity, totalAmount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issid", $foodId, $customerName, $phoneNumber, $quantity, $totalAmount);
    $stmt->execute();
    $orderId = $stmt->insert_id;

    // Trigger STK Push
    $response = initiateStkPush($phoneNumber, $totalAmount, $orderId);

    if (isset($response['CheckoutRequestID'])) {
        $checkoutRequestId = $response['CheckoutRequestID'];
        $stmt2 = $conn->prepare("INSERT INTO pushrequest (orderId, checkoutRequestId) VALUES (?, ?)");
        $stmt2->bind_param("is", $orderId, $checkoutRequestId);
        $stmt2->execute();
    }

    echo "<p>Order placed successfully. Please check your phone to complete the payment.</p>";
} else {
    header("Location: order.php");
    exit();
}
?>
