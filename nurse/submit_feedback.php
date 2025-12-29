 <?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['patient','donor','hospital'])) {
    exit("Unauthorized");
}

$user_type = $_SESSION['user_role'];
$user_id = $_SESSION['user_id'] ?? $_SESSION['hospital_id']; // For hospitals

$feedback = trim($_POST['feedback']);

if (!empty($feedback)) {
    $stmt = $conn->prepare("INSERT INTO feedback (user_type, user_id, feedback) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $user_type, $user_id, $feedback);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Feedback submitted successfully.";
}

header("Location: feedback.php");
exit();
?>
