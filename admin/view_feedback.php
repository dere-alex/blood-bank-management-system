<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    exit("Unauthorized");
}

// Fetch all feedback
$result = $conn->query("SELECT * FROM feedback ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>All Feedback</title>
<style>
table { border-collapse: collapse; width: 100%; }
th, td { border: 1px solid #ccc; padding: 8px; }
</style>
</head>
<body>

<h2>All Feedback</h2>
<table>
<tr>
    <th>ID</th>
    <th>User Type</th>
    <th>User ID</th>
    <th>Feedback</th>
    <th>Submitted At</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= ucfirst($row['user_type']) ?></td>
    <td><?= $row['user_id'] ?></td>
    <td><?= htmlspecialchars($row['feedback']) ?></td>
    <td><?= $row['created_at'] ?></td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
