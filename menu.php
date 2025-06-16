<?php
session_start();
require_once("includes/db_connect.php");

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit;
}

$foods = $pdo->query("SELECT * FROM food")->fetchAll();

?>

<!DOCTYPE html>
<html>
<head><title>Menu | Food Order</title></head>
<body>
<h2>Food Menu</h2>

<a href="order_history.php">View Order History</a> | <a href="logout.php">Logout</a><br><br>

<form method="post" action="place_order.php">
    <label for="foodId">Select Food:</label>
    <select name="foodId" id="foodId" required>
        <option value="">--Choose an item--</option>
        <?php foreach ($foods as $food): ?>
            <option value="<?= htmlspecialchars($food['foodId']) ?>">
                <?= htmlspecialchars($food['name']) ?> - KES <?= number_format($food['price'], 2) ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    Quantity:<br>
    <input type="number" name="quantity" min="1" value="1" required><br><br>

    <button type="submit">Place Order & Pay</button>
</form>
</body>
</html>
