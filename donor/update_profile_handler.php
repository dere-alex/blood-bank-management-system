 <?php
session_start();
require 'config.php';

// Only patient or donor allowed
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['patient','donor'])) {
    exit("Unauthorized");
}

$user_id = $_SESSION['user_id'];

// Get submitted data
$fullname = trim($_POST['fullname']);
$phone = trim($_POST['phone']);
$email = trim($_POST['email']);
$address = trim($_POST['address']);
$password = trim($_POST['password']); // optional

// Basic validation
if (empty($fullname) || empty($phone) || empty($email) || empty($address)) {
    $_SESSION['msg'] = "All fields except password are required.";
    header("Location: update_profile.php");
    exit();
}

// Build query depending on whether password is set
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("UPDATE userss SET fullname=?, phone=?, email=?, address=?, password=? WHERE id=?");
    $stmt->bind_param("sssssi", $fullname, $phone, $email, $address, $hashed_password, $user_id);
} else {
    $stmt = $conn->prepare("UPDATE userss SET fullname=?, phone=?, email=?, address=? WHERE id=?");
    $stmt->bind_param("ssssi", $fullname, $phone, $email, $address, $user_id);
}

$stmt->execute();
$stmt->close();

$_SESSION['msg'] = "Profile updated successfully.";
header("Location: update_profile.php");
exit();
?>
