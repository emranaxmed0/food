<?php
require_once("includes/db_connect.php");
include_once("templates/nav.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = floatval($_POST['price']);
    $image = $conn->real_escape_string($_POST['image']); // Can be a file URL or filename

    $conn->query("INSERT INTO food (name, description, price, image) VALUES ('$name', '$description', '$price', '$image')");
    header("Location: admin_food.php");
    exit();
}
?>
<div class="content">
    <h1>Add New Food</h1>
    <form method="POST">
        <label>Name:</label><br><input type="text" name="name" required><br>
        <label>Description:</label><br><textarea name="description" required></textarea><br>
        <label>Price (KES):</label><br><input type="number" step="0.01" name="price" required><br>
        <label>Image URL (optional):</label><br><input type="text" name="image"><br>
        <input type="submit" value="Add Food">
    </form>
</div>
<?php include_once("templates/footer.php"); ?>
