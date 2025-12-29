<?php
session_start();

// Protect page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'donor') {
    die("Unauthorized access");
}

require_once "config.php";

$donor_id = $_SESSION['user_id'];

// Fetch donor appointments
$stmt = $conn->prepare("
    SELECT 
        id,
        donation_date,
        donation_time,
        donation_center,
        status
    FROM donation_appointments
    WHERE donor_id = ?
    ORDER BY donation_date DESC
");

$stmt->bind_param("i", $donor_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Donation Appointments</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }
        th {
            background: #f2f2f2;
        }
    </style>
</head>
<body>

<h2>My Donation Appointments</h2>

<?php if ($result->num_rows > 0): ?>

<table>
    <tr>
        <th>ID</th>
        <th>Date</th>
        <th>Time</th>
        <th>Donation Center</th>
        <th>Status</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo htmlspecialchars($row['donation_date']); ?></td>
        <td><?php echo htmlspecialchars($row['donation_time']); ?></td>
        <td><?php echo htmlspecialchars($row['donation_center']); ?></td>
        <td>
            <?php
                if ($row['status'] === 'pending') {
                    echo "<span style='color:orange;'>Pending</span>";
                } elseif ($row['status'] === 'approved') {
                    echo "<span style='color:green;'>Approved</span>";
                } elseif ($row['status'] === 'completed') {
                    echo "<span style='color:blue;'>Completed</span>";
                } elseif ($row['status'] === 'rejected') {
                    echo "<span style='color:red;'>Rejected</span>";
                } else {
                    echo "Unknown";
                }
            ?>
        </td>
    </tr>
    <?php endwhile; ?>

</table>

<?php else: ?>

<p>You have no donation appointments yet.</p>

<?php endif; ?>

</body>
</html>
