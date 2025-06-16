<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$orderId = $_GET['orderId'] ?? null;
$checkoutRequestId = $_GET['checkoutRequestId'] ?? null;

if (!$orderId || !$checkoutRequestId) {
    exit('Missing parameters.');
}

require_once '../mpesa/stk_query.php';

$statusResponse = querySTKStatus($checkoutRequestId);

if (isset($statusResponse['ResultCode'])) {
    if ($statusResponse['ResultCode'] == 0) {
        // Payment success: update orders table
        $mpesaReceiptNumber = $statusResponse['CheckoutResultCode'] == 0 
            ? $statusResponse['ReceiptNumber'] ?? null 
            : null;
        $transactionDate = date('Y-m-d H:i:s');

        $stmt = $pdo->prepare("UPDATE orders SET paymentStatus = 'Paid', mpesaReceiptNumber = ?, transactionDate = ? WHERE orderId = ?");
        $stmt->execute([$mpesaReceiptNumber, $transactionDate, $orderId]);

        $message = "Payment successful! Receipt: " . ($mpesaReceiptNumber ?? 'N/A');
    } else {
        $message = "Payment failed or cancelled. ResultCode: " . $statusResponse['ResultCode'];
    }
} else {
    $message = "Could not fetch payment status.";
}

?>

<!DOCTYPE html>
<html>
<head><title>Payment Status</title></head>
<body>
<h2>Payment Status</h2>
<p><?= htmlspecialchars($message) ?></p>
<p><a href="menu.php">Back to Menu</a> | <a href="order_history.php">Order History</a></p>
</body>
</html>
