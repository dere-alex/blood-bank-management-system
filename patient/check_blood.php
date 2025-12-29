<?php
include 'config.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_group = $_POST['blood_group'];

    $stmt = $conn->prepare("SELECT SUM(quantity) AS total FROM blood_inventory WHERE blood_group=? AND status='available'");
    $stmt->bind_param("s", $blood_group);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    $available = $result['total'] ? $result['total'] : 0;

    if ($available > 0) {
        echo "Blood Group <strong>$blood_group</strong> is available. Total units: <strong>$available</strong>.";
    } else {
        echo "Sorry, Blood Group <strong>$blood_group</strong> is currently unavailable.";
    }

    $stmt->close();
}
$conn->close();
?>
