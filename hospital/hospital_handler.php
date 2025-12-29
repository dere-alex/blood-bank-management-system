<?php
session_start();
require_once "config.php"; // your database connection

if (!isset($_POST['register_hospital'])) {
    header("Location: createaccount.php");
    exit();
}

$hospital_name = trim($_POST['hospital_name']);
$hospital_email = trim($_POST['hospital_email']);
$hospital_phone = trim($_POST['hospital_phone']);
$hospital_address = trim($_POST['hospital_address']);
$password = $_POST['hospital_password'];
$confirm = $_POST['hospital_confirm_password'];

if ($password !== $confirm) {
    $_SESSION['user_error'] = "Passwords do not match!";
    header("Location: createaccount.php");
    exit();
}

// check if email already exists
$stmt = $conn->prepare("SELECT id FROM hospitals WHERE hospital_email = ?");
$stmt->bind_param("s", $hospital_email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $_SESSION['user_error'] = "Email already registered. Please login.";
    header("Location: createaccount.php");
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("
    INSERT INTO hospitals (hospital_name, hospital_email, hospital_phone, hospital_address, password, status)
    VALUES (?, ?, ?, ?, ?, 'pending')
");
$stmt->bind_param("sssss", $hospital_name, $hospital_email, $hospital_phone, $hospital_address, $hashed);

if ($stmt->execute()) {
    $_SESSION['user_success'] = "Hospital account created successfully. Please login.";
    header("Location: createaccount.php");
} else {
    $_SESSION['user_error'] = "Registration failed. Please try again.";
    header("Location: createaccount.php");
}
exit();
