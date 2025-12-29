 <?php
session_start();
require_once "config.php";

// Check if user is logged in
if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['user_role']; // donor, patient, staff, hospital, admin

// Fetch announcements for this role OR for all users
$sql = "SELECT title, message, file_path, file_type, created_at 
        FROM posts 
        WHERE target_role=? OR target_role='all' 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_role);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Announcements</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f0f2f5; padding: 20px; }
        .announcement { background: #fff; padding: 15px; margin-bottom: 10px; border-radius: 6px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        .announcement h3 { margin: 0 0 5px; color: #007BFF; }
        .announcement p { margin: 5px 0; }
        .announcement a { color: #28A745; text-decoration: none; }
        .announcement a:hover { text-decoration: underline; }
        .timestamp { font-size: 0.85em; color: #666; }
    </style>
</head>
<body>

<h2>Announcements</h2>

<?php if ($result->num_rows === 0): ?>
    <p>No announcements available for you.</p>
<?php else: ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="announcement">
            <?php if (!empty($row['title'])): ?>
                <h3><?= htmlspecialchars($row['title']) ?></h3>
            <?php endif; ?>
            <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
            <?php if (!empty($row['file_path'])): ?>
                <a href="<?= htmlspecialchars($row['file_path']) ?>" target="_blank">Download Attachment</a>
            <?php endif; ?>
            <p class="timestamp"><?= date("d M Y, H:i", strtotime($row['created_at'])) ?></p>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

</body>
</html>
