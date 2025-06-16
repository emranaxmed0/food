<?php
session_start();
require_once("includes/db_connect.php");
include_once("templates/nav.php");

// Fetch food items
$foods = [];
$sql = "SELECT * FROM food ORDER BY dateCreated DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $foods = $result->fetch_all(MYSQLI_ASSOC);
}
?>

<link rel="stylesheet" href="css/style.css">

<div class="banner">
    <h1>Welcome to Food Order System</h1>
</div>

<div class="row">
    <div class="content">
        <h2>Available Food Items</h2>
        <?php if (count($foods) > 0): ?>
            <div class="food-list">
                <?php foreach ($foods as $food): ?>
                    <div class="food-item">
                        <h3><?php echo htmlspecialchars($food['name']); ?></h3>
                        <p><?php echo htmlspecialchars($food['description']); ?></p>
                        <p><strong>Price:</strong> KES <?php echo number_format($food['price'], 2); ?></p>
                        <?php if (!empty($food['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($food['image']); ?>" alt="<?php echo htmlspecialchars($food['name']); ?>" width="150">
                        <?php endif; ?>
                        <br><br>
                        <a href="order.php?foodId=<?php echo $food['foodId']; ?>">Order Now</a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>No food items available at the moment.</p>
        <?php endif; ?>
    </div>
</div>

<?php include_once("templates/footer.php"); ?>