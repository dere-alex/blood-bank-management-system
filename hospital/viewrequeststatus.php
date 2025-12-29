<?php
session_start();
require_once "config.php";

// Only patient, hospital, or nurse allowed
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['patient','hospital','nurse'])) {
    exit("Unauthorized");
}

// Determine user type and ID
$user_type = $_SESSION['user_role'];
if ($user_type === 'hospital') {
    $user_id = $_SESSION['hospital_id'];
} else {
    $user_id = $_SESSION['user_id'];
}

// Fetch user requests
$stmt = $conn->prepare("
    SELECT r.*, 
           i.fullname AS processed_by_name
    FROM blood_requests r
    LEFT JOIN staff i ON r.processed_by=i.id
    WHERE r.requester_type=? AND r.requester_id=?
    ORDER BY r.requested_at DESC
");
$stmt->bind_param("si", $user_type, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Blood Requests</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
.status-pending { color: orange; }
.status-approved { color: green; }
.status-rejected { color: red; }
</style>
</head>
<body>

<h2>My Blood Requests</h2>

<table>
<tr>
<th>ID</th>
<th>Blood Group</th>
<th>Quantity</th>
<th>Status</th>
<th>Processed By</th>
<th>Requested At</th>
<th>Processed At</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['blood_group'] ?></td>
<td><?= $row['quantity'] ?></td>
<td class="status-<?= $row['status'] ?>"><?= ucfirst($row['status']) ?></td>
<td><?= $row['processed_by_name'] ?? '-' ?></td>
<td><?= $row['requested_at'] ?></td>
<td><?= $row['processed_at'] ?? '-' ?></td>
</tr>
<?php endwhile; ?>
</table>

</body>
</html>
