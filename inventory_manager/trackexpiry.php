<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'inventory manager') {
    exit("<p style='color:red'>Unauthorized access</p>");
}

require_once "config.php";

$today = date('Y-m-d');

$sql = "
SELECT 
    blood_group,
    quantity,
    collection_date,
    expiry_date,
    DATEDIFF(expiry_date, ?) AS days_left
FROM blood_inventory
ORDER BY expiry_date ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Blood Expiry Tracking</h2>

<table style="width:100%; border-collapse:collapse;">
    <tr style="background:#eee;">
        <th>Blood Group</th>
        <th>Quantity</th>
        <th>Collection Date</th>
        <th>Expiry Date</th>
        <th>Status</th>
    </tr>

<?php while ($row = $result->fetch_assoc()): 

    if ($row['days_left'] < 0) {
        $status = "<span style='color:red;font-weight:bold'>Expired</span>";
    } elseif ($row['days_left'] <= 3) {
        $status = "<span style='color:orange;font-weight:bold'>Expiring Soon</span>";
    } else {
        $status = "<span style='color:green;font-weight:bold'>Valid</span>";
    }
?>

<tr>
    <td><?= htmlspecialchars($row['blood_group']) ?></td>
    <td><?= $row['quantity'] ?></td>
    <td><?= $row['collection_date'] ?></td>
    <td><?= $row['expiry_date'] ?></td>
    <td><?= $status ?></td>
</tr>

<?php endwhile; ?>

</table>

<?php
$stmt->close();
$conn->close();
?>
