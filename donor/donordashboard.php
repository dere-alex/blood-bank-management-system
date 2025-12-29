<?php
session_start();

// Protect page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'donor') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donor Dashboard - IBBMS</title>
    <link rel="stylesheet" href="donordashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Donor Panel</h2>
    <a href="#" data-page="home">Home</a>
    <a href="#" data-page="make_donation">Make Donation Appointment</a>
     <a href="#" data-page= "viewinfo">View Announcements</a>

    <a href="#" data-page="update_profile">Update Profile</a>
    <a href="#" data-page="feedback">Give Feedback</a>
    <a href="#" data-page="donor_appointments">View Appointment Status</a>

    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <div id="pageContent">
        <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Select an operation from the left menu.</p>
    </div>
</div>

<script>
// ====== MENU CLICK HANDLER ======
document.querySelectorAll(".sidebar a[data-page]").forEach(link => {
    link.addEventListener("click", function(e){
        e.preventDefault();
        const page = this.dataset.page;
        if(page === "home") {
            document.getElementById("pageContent").innerHTML = `
                <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                <p>Select an operation from the left menu.</p>
            `;
        } else {
            loadPage(page + ".php");
        }
    });
});

// ====== LOAD PAGE INTO DASHBOARD ======
function loadPage(pageFile) {
    fetch(pageFile)
    .then(res => res.text())
    .then(html => {
        document.getElementById("pageContent").innerHTML = html;
    })
    .catch(() => {
        document.getElementById("pageContent").innerHTML = "<p style='color:red;'>Page not found.</p>";
    });
}
</script>

</body>
</html>

