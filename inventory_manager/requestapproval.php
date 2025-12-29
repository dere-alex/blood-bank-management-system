<?php
session_start();
require_once "config.php";

// Only Inventory Manager
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'inventory manager') {
    exit("Unauthorized access");
}

// Approve or reject
if (isset($_GET['action'], $_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'] === 'approve' ? 'approved' : 'rejected';
    $processed_by = $_SESSION['user_id'];
    $processed_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("UPDATE blood_requests SET status=?, processed_by=?, processed_at=? WHERE id=?");
    $stmt->bind_param("sisi", $action, $processed_by, $processed_at, $id);
    $stmt->execute();
    $stmt->close();

    $_SESSION['msg'] = "Request $action successfully.";
    header("Location: request_approval.php");
    exit();
}

// Fetch all pending requests
$result = $conn->query("
    SELECT r.*, 
           u.fullname AS patient_name,
           h.hospital_name
    FROM blood_requests r
    LEFT JOIN userss u ON r.requester_type='patient' AND r.requester_id=u.id
    LEFT JOIN hospitals h ON r.requester_type='hospital' AND r.requester_id=h.id
    ORDER BY requested_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Inventory Manager - Approve Requests</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
button { padding: 5px 10px; cursor: pointer; margin:2px; }
.success { color: green; }
</style>
</head>
<body>

<h2>Blood Requests</h2>
<?php if (!empty($_SESSION['msg'])) { echo "<p class='success'>{$_SESSION['msg']}</p>"; unset($_SESSION['msg']); } ?>

<table>
<tr>
<th>ID</th>
<th>Requester</th>
<th>Type</th>
<th>Blood Group</th>
<th>Quantity</th>
<th>Status</th>
<th>Requested At</th>
<th>Action</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['requester_type']=='patient' ? htmlspecialchars($row['patient_name']) : htmlspecialchars($row['hospital_name']) ?></td>
<td><?= $row['requester_type'] ?></td>
<td><?= $row['blood_group'] ?></td>
<td><?= $row['quantity'] ?></td>
<td><?= $row['status'] ?></td>
<td><?= $row['requested_at'] ?></td>
<td>
<?php if ($row['status'] === 'pending'): ?>
<a href="requestapproval.php?id=<?= $row['id'] ?>&action=approve"><button>Approve</button></a>
<a href="requestapproval.php?id=<?= $row['id'] ?>&action=reject"><button>Reject</button></a>
<?php else: ?>
Processed
<?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
