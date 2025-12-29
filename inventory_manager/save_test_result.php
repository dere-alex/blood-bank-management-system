<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'lab technician') {
    echo "<p style='color:red'>Unauthorized</p>";
    exit();
}

$labtech_id = $_SESSION['user_id'];


$person_type = $_POST['person_type'] ?? '';
$person_id   = $_POST['person_id'] ?? '';
$test_name   = trim($_POST['test_name'] ?? '');
$result      = $_POST['result'] ?? '';

if (!$person_type || !$person_id || !$test_name || !$result) {
    echo "<p style='color:red'>All fields are required</p>";
    exit();
}

$stmt = $conn->prepare("
    INSERT INTO test_results 
    (person_type, patient_or_donor_id, test_name, result, test_date, labtech_id, status)
    VALUES (?, ?, ?, ?, NOW(), ?, 'pending')
");

$stmt->bind_param(
    "sissi",
    $person_type,
    $person_id,
    $test_name,
    $result,
    $labtech_id
);

if ($stmt->execute()) {
    echo "<p style='color:green'>Test result saved successfully</p>";
} else {
    echo "<p style='color:red'>Database error</p>";
}
