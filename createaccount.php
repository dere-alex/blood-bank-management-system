<?php 
session_start();

$user_error = $_SESSION['user_error'] ?? '';
$user_success = $_SESSION['user_success'] ?? '';
$hospital_error = $_SESSION['hospital_error'] ?? '';
$hospital_success = $_SESSION['hospital_success'] ?? '';

function showMessage($msg, $type="error") {
    return !empty($msg) ? "<p class='{$type}-message'>$msg</p>" : '';
}

// Clear session messages
unset($_SESSION['user_error'], $_SESSION['user_success'], $_SESSION['hospital_error'], $_SESSION['hospital_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Account - IBBMS</title>
    <link rel="stylesheet" href="createaccount.css">
</head>
<body>

<div class="container">

    <!-- ACCOUNT TYPE SELECTOR -->
    <div class="account-type">
        <button type="button" class="type-btn active" id="donorBtn">Donor / Patient</button>
        <button type="button" class="type-btn" id="hospitalBtn">Hospital</button>
    </div>

    <h2>Register</h2>
    <p class="info">Fill in your details to create your account.</p>

    <!-- DONOR / PATIENT FORM -->
    <form id="createAccountForm" action="user_handler.php" method="POST">
        <?= showMessage($user_error, "error"); ?>
        <?= showMessage($user_success, "success"); ?>

        <label>Full Name</label>
        <input type="text" name="fullname" required>

        <label>Age</label>
        <input type="number" name="age" min="18" max="45" required>

        <label>Gender</label>
        <select name="gender" required>
            <option value="">--Select--</option>
            <option>Male</option>
            <option>Female</option>
        </select>

        <label>Phone</label>
        <input type="text" name="phone" placeholder="09XXXXXXXX" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required>

        <label>Address</label>
        <input type="text" name="address" required>

        <label>Blood Type</label>
        <select name="bloodType" required>
            <option value="">Select</option>
            <option>A+</option><option>A-</option>
            <option>B+</option><option>B-</option>
            <option>O+</option><option>O-</option>
            <option>AB+</option><option>AB-</option>
        </select>

        <label>Role</label>
        <select name="role" required>
            <option value="">Select</option>
            <option>Donor</option>
            <option>Patient</option>
        </select>

        <button type="submit" name="register">Register</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>

    <!-- HOSPITAL FORM -->
    <form id="hospitalForm" class="hidden" method="POST" action="hospital_handler.php">
        <?= showMessage($hospital_error, "error"); ?>
        <?= showMessage($hospital_success, "success"); ?>

        <label>Hospital Name</label>
        <input type="text" name="hospital_name" required>

        <label>Hospital Email</label>
        <input type="email" name="hospital_email" required>

        <label>Phone</label>
        <input type="text" name="hospital_phone" required>

        <label>Address</label>
        <input type="text" name="hospital_address" required>

        <label>Password</label>
        <input type="password" name="hospital_password" required>

        <label>Confirm Password</label>
        <input type="password" name="hospital_confirm_password" required>

        <button type="submit" name="register_hospital">Register Hospital</button>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </form>

</div>

<script src="createaccount.js"></script>

<script>
    // AUTO-HIDE MESSAGES AFTER 5 SECONDS
    setTimeout(() => {
        const messages = document.querySelectorAll('.error-message, .success-message');
        messages.forEach(msg => msg.style.display = 'none');
    }, 5000);
</script>

</body>
</html>
