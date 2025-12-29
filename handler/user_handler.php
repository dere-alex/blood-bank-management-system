<?php
session_start();
require_once "config.php";

if (!isset($_POST['register'])) {
    header("Location: createaccount.php");
    exit();
}

$fullname = trim($_POST['fullname']);
$age      = $_POST['age'];
$gender   = $_POST['gender'];
$phone    = $_POST['phone'];
$email    = trim($_POST['email']);
$password = $_POST['password'];
$confirm  = $_POST['confirm_password'];
$address  = $_POST['address'];
$blood    = $_POST['bloodType'];
$role     = $_POST['role'];

if ($password !== $confirm) {
    $_SESSION['user_error'] = "Passwords do not match.";
    header("Location: createaccount.php");
    exit();
}

$hashed = password_hash($password, PASSWORD_DEFAULT);

try {

    $stmt = $conn->prepare("
        INSERT INTO userss 
        (fullname, age, gender, phone, email, password, address, bloodType, role)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sisssssss",
        $fullname,
        $age,
        $gender,
        $phone,
        $email,
        $hashed,
        $address,
        $blood,
        $role
    );

    $stmt->execute();

    $_SESSION['user_success'] = "Account created successfully. Please login.";
    header("Location: createaccount.php");
    exit();

} catch (mysqli_sql_exception $e) {

    
    if ($e->getCode() == 1062) {
        $_SESSION['user_error'] = "This email is already registered.";
    } else {
        $_SESSION['user_error'] = "Registration failed. Please try again.";
    }

    header("Location: createaccount.php");
    exit();
}
