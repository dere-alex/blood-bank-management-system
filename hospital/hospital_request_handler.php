 <?php
session_start();
require_once "config.php";

// Only hospital allowed
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hospital') {
    exit("Unauthorized");
}

$hospital_id = $_SESSION['hospital_id'];
$blood_group = $_POST['blood_group'];
$quantity = (int)$_POST['quantity'];

// Insert request into blood_requests table
$stmt = $conn->prepare("
    INSERT INTO blood_requests (requester_type, requester_id, blood_group, quantity)
    VALUES ('hospital', ?, ?, ?)
");
$stmt->bind_param("isi", $hospital_id, $blood_group, $quantity);
$stmt->execute();
$stmt->close();

// Set success message in same pattern as example
$_SESSION['msg'] = "Blood request submitted successfully.";

// Redirect back to the hospital request page
header("Location: hospital_request_blood.php");
exit();
