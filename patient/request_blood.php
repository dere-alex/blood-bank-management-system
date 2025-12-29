<?php
session_start();

// Protect page for patient or nurse
if (!isset($_SESSION['user_role']) || 
    !in_array($_SESSION['user_role'], ['patient', 'nurse'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Request Blood</title>
<link rel="stylesheet" href="blood_request.css">
<style>
.success {
    color: green;
    margin-bottom: 10px;
}
.box {
    width: 400px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
}
</style>
</head>

<body>

<div class="box">
<h2>Request Blood</h2>

<?php
if (!empty($_SESSION['msg'])) {
    echo "<p class='success'>{$_SESSION['msg']}</p>";
    unset($_SESSION['msg']);
}
?>

<form method="POST" action="request_blood_handler.php">
    <label>Blood Group</label>
    <select name="blood_group" required>
        <option value="">-- Select --</option>
        <option>A+</option><option>A-</option>
        <option>B+</option><option>B-</option>
        <option>O+</option><option>O-</option>
        <option>AB+</option><option>AB-</option>
    </select>

    <label>Units</label>
    <input type="number" name="units" min="1" max="50" required>

    <button type="submit"> Submit Request </button>
</form>

</div>

</body>
</html>
