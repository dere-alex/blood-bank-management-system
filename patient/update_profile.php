<?php
session_start();
require 'config.php';

// Only patient or donor allowed
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['patient','donor'])) {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];

// Fetch current user data
$stmt = $conn->prepare("SELECT fullname, phone, email, address FROM userss WHERE id=?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$result) {
    exit("User not found");
}

if (!empty($_SESSION['msg'])) {
    echo "<p style='color:green'>{$_SESSION['msg']}</p>";
    unset($_SESSION['msg']);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Update Profile</title>
<style>
form { max-width:500px; margin:50px auto; display:flex; flex-direction:column; }
input { margin-bottom:15px; padding:10px; }
button { padding:10px; background-color:#007bff; color:#fff; border:none; cursor:pointer; }
button:hover { background-color:#0056b3; }
</style>
</head>
<body>

<h2>Update Profile</h2>
<form method="POST" action="update_profile_handler.php">
    <input type="text" name="fullname" value="<?= htmlspecialchars($result['fullname']) ?>" placeholder="Full Name" required>
    <input type="text" name="phone" value="<?= htmlspecialchars($result['phone']) ?>" placeholder="Phone" required>
    <input type="email" name="email" value="<?= htmlspecialchars($result['email']) ?>" placeholder="Email" required>
    <input type="text" name="address" value="<?= htmlspecialchars($result['address']) ?>" placeholder="Address" required>
    <input type="password" name="password" placeholder="New Password (leave blank to keep current)">
    <button type="submit">Update Profile</button>
</form>

</body>
</html>
