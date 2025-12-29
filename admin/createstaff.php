<?php
session_start();

$staff_error = $_SESSION['staff_error'] ?? '';
$staff_success = $_SESSION['staff_success'] ?? '';

function showMessage($msg, $type="error") {
    return !empty($msg) ? "<p class='$type-message'>$msg</p>" : '';
}

unset($_SESSION['staff_error'], $_SESSION['staff_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Staff Account</title>
    <link rel="stylesheet" href="createstaff.css">
</head>
<body>

<div class="form-container">

    <h2>Create Staff Account</h2>

    <?= showMessage($staff_error, "error"); ?>
    <?= showMessage($staff_success, "success"); ?>

    <form action="staff_handler.php" method="post">

        <label>Full Name</label>
        <input type="text" name="fullname" placeholder="Enter Full Name" required>

        <label>Phone Number</label>
        <input type="text" name="phone" placeholder="09xxxxxxxx" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="example@gmail.com" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Min 6 characters" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Confirm your password" required>

        <label>Role</label>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option>Lab Technician</option>
            <option>Nurse</option>
            <option>Inventory Manager</option>
        </select>

        <button type="submit" name="create_staff">Create Staff</button>

        <p class="login-link">
            Already have an account? <a href="login.php">Login</a>
        </p>

    </form>


</div>

</body>
</html>
