<?php
require_once("includes/db_connect.php");

$id = intval($_GET['id']);
$conn->query("DELETE FROM food WHERE foodId = $id");

header("Location: admin_food.php");
exit();
