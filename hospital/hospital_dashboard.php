<?php
session_start();
require_once "config.php";

// Security
if (!isset($_SESSION['hospital_id'])) {
    header("Location: login.php");
    exit();
}

$hospital_id   = $_SESSION['hospital_id'];
$hospital_name = $_SESSION['hospital_name'] ?? 'Hospital';

// Check approval
$stmt = $conn->prepare("SELECT status FROM hospitals WHERE id=?");
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$stmt->bind_result($status);
$stmt->fetch();
$stmt->close();

if ($status !== 'approved') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Hospital Dashboard - IBBMS</title>
<link rel="stylesheet" href="hospital_dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Hospital</h2>
   <a href="#" data-page="check_blood_availability">Check Blood Availability</a>
    <a href="#" data-page="hospital_request_blood">Request Blood</a>
    <a href="#" data-page="viewrequeststatus">View Request Status</a>
   <a href="#" data-page="feedback">Give Feedback</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <div id="pageContent">
        <h1>Welcome <?php echo htmlspecialchars($hospital_name); ?></h1>
        <p>Select an option from the menu.</p>
    </div>
</div>

<script>
document.querySelectorAll(".sidebar a[data-page]").forEach(link => {
    link.addEventListener("click", e => {
        e.preventDefault();
        loadPage(link.dataset.page + ".php");
    });
});

function loadPage(page) {
    fetch(page)
        .then(res => res.text())
        .then(html => {
            const content = document.getElementById("pageContent");
            content.innerHTML = html;

            // Execute inline scripts from loaded HTML
            const scripts = content.querySelectorAll("script");
            scripts.forEach(oldScript => {
                const newScript = document.createElement("script");
                if (oldScript.src) {
                    // External script
                    newScript.src = oldScript.src;
                } else {
                    // Inline script
                    newScript.textContent = oldScript.textContent;
                }
                document.body.appendChild(newScript);
                oldScript.remove();
            });
        })
        .catch(() => {
            document.getElementById("pageContent").innerHTML =
                "<p style='color:red'>Page not found</p>";
        });
}

</script>

</body>
</html>

