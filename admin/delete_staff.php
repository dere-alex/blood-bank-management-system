 <?php
session_start();
require_once "config.php";   // make sure this connects to MySQL as $conn



// Get input values
$staff_id = isset($_POST['staff_id']) ? trim($_POST['staff_id']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';

// At least one field must be provided
if ($staff_id === '' && $email === '') {
    die("Please enter Staff ID or Staff Email");
}

/*
 |---------------------------------------------------------
 | Priority 1: Delete by ID (safer and professional)
 |---------------------------------------------------------
 */
if ($staff_id !== '') {

    $staff_id = (int) $staff_id;

    if ($staff_id <= 0) {
        die("Invalid Staff ID");
    }

    $sql = "DELETE FROM staff WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $staff_id);

}
/*
 |---------------------------------------------------------
 | Priority 2: Delete by Email (only if ID is empty)
 |---------------------------------------------------------
 */
else {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email address");
    }

    $sql = "DELETE FROM staff WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
}

// Execute
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "Staff deleted successfully.";
} else {
    echo "No staff found with the provided information.";
}

$stmt->close();
$conn->close();
?>
