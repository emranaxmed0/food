<?php
// Make sure the path is correct
require_once("../includes/db_connect.php");

// Get raw POST body
$rawData = file_get_contents("php://input");

// Log raw data for inspection
file_put_contents("callback_debug.txt", "RAW DATA:\n" . $rawData . "\n\n", FILE_APPEND);

// Try to decode JSON
$callbackData = json_decode($rawData, true);

// If JSON is invalid, log and respond
if (json_last_error() !== JSON_ERROR_NONE) {
    file_put_contents("callback_debug.txt", "JSON ERROR: " . json_last_error_msg() . "\n\n", FILE_APPEND);
    http_response_code(400);
    echo json_encode(["message" => "Invalid JSON"]);
    exit;
}

// Log decoded data
file_put_contents("callback_debug.txt", "DECODED:\n" . print_r($callbackData, true) . "\n\n", FILE_APPEND);

// Extract info
$checkoutRequestId = $callbackData['Body']['stkCallback']['CheckoutRequestID'] ?? null;
$resultCode = $callbackData['Body']['stkCallback']['ResultCode'] ?? null;
$resultDesc = $callbackData['Body']['stkCallback']['ResultDesc'] ?? null;

// Update your DB
if ($checkoutRequestId) {
    $stmt = $conn->prepare("UPDATE pushrequest SET lastUpdated = NOW() WHERE checkoutRequestId = ?");
    $stmt->bind_param("s", $checkoutRequestId);
    $stmt->execute();
}

// Respond to Safaricom (must send this back)
echo json_encode([
    "ResultCode" => 0,
    "ResultDesc" => "Callback received successfully"
]);
?>
