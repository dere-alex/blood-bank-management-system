<?php
session_start();
require_once "config.php";

// Check admin login
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Approve hospital
if (isset($_GET['approve_id'])) {
    $id = (int)$_GET['approve_id'];
    $conn->query("UPDATE hospitals SET status='approved' WHERE id=$id");
    $_SESSION['msg'] = "Hospital approved successfully.";
    header("Location: hospitalapproval.php");
    exit();
}

// Fetch pending hospitals
$result = $conn->query("SELECT id, hospital_name, hospital_email, hospital_address FROM hospitals WHERE status='pending'");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Hospital Approval</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
button { padding: 5px 10px; cursor: pointer; }
.success { color: green; }
</style>
</head>
<body>

<h2>Pending Hospitals</h2>
<?php if (!empty($_SESSION['msg'])) { echo "<p class='success'>{$_SESSION['msg']}</p>"; unset($_SESSION['msg']); } ?>

<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Address</th>
<th>Action</th>
</tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['hospital_name']) ?></td>
<td><?= htmlspecialchars($row['hospital_email']) ?></td>
<td><?= htmlspecialchars($row['hospital_address']) ?></td>
<td>
<a href="hospitalapproval.php?approve_id=<?= $row['id'] ?>"><button>Approve</button></a>

</td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
