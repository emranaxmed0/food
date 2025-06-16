<?php
session_start();
include_once("templates/nav.php");
?>

<div class="container">
    <h1>Welcome, <?= $_SESSION['username'] ?? 'Guest' ?>!</h1>
    <p>This is your dashboard. Use the navigation above to browse through the system.</p>
</div>

<?php include_once("templates/footer.php"); ?>
