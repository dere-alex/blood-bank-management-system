<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<div class="container">
    <div class="form-box">
        <h2>Login</h2>

        <?php
        if (!empty($_SESSION['login_error'])) {
            echo "<p class='error-message'>{$_SESSION['login_error']}</p>";
            unset($_SESSION['login_error']);
        }
        ?>

        <div>
      <form action="login_handler.php" method="POST">
    <label>Email</label>
    <input type="email" name="email" placeholder="Enter your Email..." required>

    <label>Password</label>
    <input type="password" name="password" placeholder="Enter your password..." required>

    <button type="submit" name="login">Login</button>
</form>

    </div>

        <p id="msg"></p>
    </div>
</div>

<script src="login.js"></script>
</body>
</html>


