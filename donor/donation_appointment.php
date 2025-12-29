<?php
require_once "config.php";

session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'donor') {
    die("Unauthorized access");
}




$donor_id = $_SESSION['user_id'];

$donation_date = $_POST['donation_date'];
$donation_time = $_POST['donation_time'];
$donation_center = trim($_POST['donation_center']);
$last_donation_date = !empty($_POST['last_donation_date']) ? $_POST['last_donation_date'] : NULL;

// Basic validation
if (empty($donation_date) || empty($donation_time) || empty($donation_center)) {
    die("All required fields must be filled");
}

$sql = "INSERT INTO donation_appointments 
        (donor_id, donation_date, donation_time, donation_center, last_donation_date)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "issss",
    $donor_id,
    $donation_date,
    $donation_time,
    $donation_center,
    $last_donation_date
);

if ($stmt->execute()) {
    echo "Donation appointment booked successfully. Waiting for approval.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
