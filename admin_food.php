<?php
require_once("includes/db_connect.php");
include_once("templates/nav.php");

$query = "SELECT * FROM food ORDER BY dateCreated DESC";
$result = $conn->query($query);
?>
<div class="content">
    <h1>Food Items</h1>
    <a href="add_food.php" class="btn">Add New Food</a>
    <table>
        <tr><th>Name</th><th>Description</th><th>Price</th><th>Actions</th></tr>
        <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['description']) ?></td>
            <td>KES <?= number_format($row['price'], 2) ?></td>
            <td>
                <a href="edit_food.php?id=<?= $row['foodId'] ?>">Edit</a> |
                <a href="delete_food.php?id=<?= $row['foodId'] ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php include_once("templates/footer.php"); ?>
