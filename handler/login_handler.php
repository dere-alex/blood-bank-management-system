<?php
session_start();
require_once "config.php";

if (!isset($_POST['login'])) {
    header("Location: login.php");
    exit();
}

$email = trim($_POST['email']);
$password = $_POST['password'];

/* ======================
   ADMIN LOGIN (HARDCODED)
====================== */
if ($email === "admin@gmail.com" && $password === "admin123") {
    session_regenerate_id(true);
    $_SESSION['user_id'] = 0;
    $_SESSION['user_name'] = "System Admin";
    $_SESSION['user_role'] = "admin";
    header("Location: admin_dashboard.php");
    exit();
}

/* ======================
   HOSPITAL LOGIN
====================== */
$stmt = $conn->prepare("SELECT id, hospital_name, password, status FROM hospitals WHERE hospital_email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $hospital = $result->fetch_assoc();

    if (!password_verify($password, $hospital['password'])) {
        $_SESSION['login_error'] = "Incorrect password!";
        header("Location: login.php");
        exit();
    }

    if ($hospital['status'] !== 'approved') {
        $_SESSION['login_error'] = "Your hospital account is pending admin approval.";
        header("Location: login.php");
        exit();
    }

    session_regenerate_id(true);
    $_SESSION['hospital_id'] = $hospital['id'];
    $_SESSION['hospital_name'] = $hospital['hospital_name'];
    $_SESSION['user_role'] = "hospital";

    header("Location: hospital_dashboard.php");
    exit();
}

/* ======================
   STAFF LOGIN
====================== */
$stmt = $conn->prepare("SELECT id, fullname, password, role FROM staff WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $staff = $result->fetch_assoc();

    if (!password_verify($password, $staff['password'])) {
        $_SESSION['login_error'] = "Incorrect password!";
        header("Location: login.php");
        exit();
    }

    session_regenerate_id(true);

    // Common session variables
    $_SESSION['user_id'] = $staff['id'];
    $_SESSION['user_name'] = $staff['fullname'];
    $_SESSION['user_role'] = trim(strtolower($staff['role']));

    // Add this for Lab Technician pages
    if ($_SESSION['user_role'] === 'lab technician') {
        $_SESSION['labtech_id'] = $staff['id'];
    }

    switch ($_SESSION['user_role']) {
        case 'lab technician':
            header("Location: labtech_dashboard.php");
            break;
        case 'nurse':
            header("Location: nursedashboard.php");
            break;
        case 'inventory manager':
            header("Location: inventorymanager_dashboard.php");
            break;
    }
    exit();
}

/* ======================
   DONOR / PATIENT LOGIN
====================== */
$stmt = $conn->prepare("SELECT id, fullname, password, role FROM userss WHERE email=?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        $_SESSION['login_error'] = "Incorrect password!";
        header("Location: login.php");
        exit();
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['fullname'];
    $_SESSION['user_role'] = strtolower($user['role']);

    if ($_SESSION['user_role'] === 'donor') {
        header("Location: donordashboard.php");
    } else {
        header("Location: patientdashboard.php");
    }
    exit();
}

/* ======================
   NOT FOUND
====================== */
$_SESSION['login_error'] = "Account not found!";
header("Location: login.php");
exit();
