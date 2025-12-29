<?php
session_start();
require_once "config.php";

// Only patient or nurse allowed
if (!isset($_SESSION['user_role']) || 
    !in_array($_SESSION['user_role'], ['patient', 'nurse'])) {
    header("Location: login.php");
    exit();
}

$requester_type = $_SESSION['user_role']; // 'patient' or 'nurse'
$requester_id = $_SESSION['user_id'];

$blood_group = $_POST['blood_group'];
$units = (int)$_POST['units'];

// Insert request
$stmt = $conn->prepare("
    INSERT INTO blood_requests (requester_type, requester_id, blood_group, quantity)
    VALUES (?, ?, ?, ?)
");
$stmt->bind_param("sisi", $requester_type, $requester_id, $blood_group, $units);
$stmt->execute();
$stmt->close();

$_SESSION['msg'] = "Blood request submitted successfully.";

header("Location: request_blood.php");
exit();

