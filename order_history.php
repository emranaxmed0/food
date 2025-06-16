<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$phone = $_SESSION['user']['phone'];

$stmt = $pdo->prepare("
    SELECT o.*, f.name AS foodName 
    FROM orders o 
    JOIN food f ON o.foodId = f.foodId
    WHERE o.phoneNumber = ? 
    ORDER BY o.dateCreated DESC
");

$stmt->execute([$phone]);
$orders = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head><title>Order History</title></head>
<body>
<h2>Order History</h2>

<a href="menu.php">Back to Menu</a><br><br>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Order ID</th>
            <th>Food</th>
            <th>Quantity</th>
            <th>Total (KES)</th>
            <th>Payment Status</th>
            <th>M-PESA Receipt</th>
            <th>Order Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($orders as $order): ?>
        <tr>
            <td><?= htmlspecialchars($order['orderId']) ?></td>
            <td><?= htmlspecialchars($order['foodName']) ?></td>
            <td><?= htmlspecialchars($order['quantity']) ?></td>
            <td><?= number_format($order['totalAmount'], 2) ?></td>
            <td><?= htmlspecialchars($order['paymentStatus']) ?></td>
            <td><?= htmlspecialchars($order['mpesaReceiptNumber'] ?? '-') ?></td>
            <td><?= htmlspecialchars($order['dateCreated']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>
