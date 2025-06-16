<link rel="stylesheet" href="css/style.css">
<?php
    session_start();
    require_once("includes/db_connect.php");
    include_once("templates/nav.php");

    if (isset($_POST["signin"])) {
        $username = mysqli_real_escape_string($conn, addslashes(strtolower($_POST["username"])));
        $passphrase = mysqli_real_escape_string($conn, $_POST["passphrase"]);

        // Query to check if the user exists
        $query = "SELECT * FROM users WHERE Username='$username' LIMIT 1";
        $result = $conn->query($query);

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            // Verify the password
            if (password_verify($passphrase, $user['Password'])) {
                // Password is correct, set session variables and redirect to a welcome page or dashboard
                $_SESSION["userid"] = $user["UserID"];
                $_SESSION["username"] = $user["Username"];
                header("Location: welcome.php"); // Change to the appropriate welcome page or dashboard
                exit();
            } else {
                $_SESSION["signin_error"] = "Invalid username or password.";
            }
        } else {
            $_SESSION["signin_error"] = "Invalid username or password.";
        }
    }
?>
<div class="banner">
    <h1>Sign In to SprintGyms</h1>
</div>
<div class="row">
    <div class="content">
        <h1>Sign In Form</h1>
        <form action="<?php print htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" autocomplete="off" class="contact_us">
            <?php if (isset($_SESSION["signin_error"])) { print "<span class='error_form'>" . $_SESSION["signin_error"] . "</span>"; unset($_SESSION["signin_error"]); } ?><br>
            
            <label for="username">Username:</label><br>
            <input type="text" name="username" id="username" placeholder="Username" maxlength="50" required autofocus><br><br>
            
            <label for="password">Password:</label><br>
            <input type="password" name="passphrase" id="password" placeholder="Password" required><br><br>

            <input type="submit" name="signin" value="Sign In">
        </form>
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
    </div>
</div>
<?php include_once("templates/footer.php"); ?>
