<?php
require_once("includes/db_connect.php");
include("templates/nav.php");

$query = "SELECT * FROM food";
$result = $conn->query($query);
?>

<div class="container">
    <h1 style="text-align:center; color:#d9232d;">Our KFC-Style Menu</h1>
    <div class="food-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="food-card">
                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="Food Image">
                <div class="food-card-content">
                    <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                    <p class="price">KSh <?php echo number_format($row['price'], 2); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include("templates/footer.php"); ?>
