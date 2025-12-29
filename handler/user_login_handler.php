 <?php
session_start();
require_once "config.php"; // database connection

// CHECK IF LOGIN BUTTON IS CLICKED
if (!isset($_POST['login'])) {
    header("Location: login.php");
    exit();
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// VALIDATION
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['login_error'] = "Invalid email format!";
    header("Location: login.php");
    exit();
}

if (empty($password)) {
    $_SESSION['login_error'] = "Password is required!";
    header("Location: login.php");
    exit();
}

// FIND USER IN DATABASE
$stmt = $conn->prepare("SELECT id, fullname, password, role FROM userss WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $_SESSION['login_error'] = "No account found with this email!";
    header("Location: login.php");
    exit();
}

$user = $result->fetch_assoc();

// VERIFY PASSWORD
if (!password_verify($password, $user['password'])) {
    $_SESSION['login_error'] = "Incorrect password!";
    header("Location: login.php");
    exit();
}

// LOGIN SUCCESS — SAVE SESSION
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['fullname'];
$_SESSION['user_role'] = $user['role'];

// REDIRECT BASED ON ROLE
switch (strtolower($user['role'])) {
    case "donor":
        header("Location: donordashboard.html"); // create this dashboard
        break;

    case "patient":
        header("Location: patientdashboard.html"); // create this dashboard
        break;

    default:
        $_SESSION['login_error'] = "Unknown role!";
        header("Location: login.php");
        break;
}

exit();
?>
