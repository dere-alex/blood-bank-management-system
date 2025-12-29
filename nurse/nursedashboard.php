<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'nurse') {
    header("Location: login.php");
    exit();
}
$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Nurse Dashboard - IBBMS</title>
    <link rel="stylesheet" href="nursedashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>Nurse Panel</h2>
    <a href="#" data-page="donationapproval">Donation Approval</a>
    <a href="#" data-page="view_test_results">View Test Results</a>
    <a href="#" data-page="request_blood">Request Blood Unit</a>
      <a href="#" data-page="viewrequeststatus">View Request Status</a>
    
    <a href="#" data-page="view_announcements">View Announcements</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <div id="pageContent">
        <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Select an operation from the left menu.</p>
    </div>
</div>

<script>
// ================= MENU CLICK HANDLER =================
document.querySelectorAll(".sidebar a[data-page]").forEach(link => {
    link.addEventListener("click", function(e){
        e.preventDefault();
        const page = this.dataset.page;
        if(page === "home") {
            document.getElementById("pageContent").innerHTML = `
                <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                <p>Select an operation from the left menu.</p>
            `;
        } else if(page === "view_announcements") {
            loadPage("viewinfo.php?role=<?php echo $user_role; ?>");
        } else {
            loadPage(page + ".php");
        }
    });
});

// ================= LOAD PAGE =================
function loadPage(file) {
    fetch(file)
        .then(res => res.text())
        .then(html => {
            document.getElementById("pageContent").innerHTML = html;
            // Run any JS init functions declared in loaded page
            if(typeof initPage === "function") initPage();
        })
        .catch(() => {
            document.getElementById("pageContent").innerHTML = "<p style='color:red;'>Page not found.</p>";
        });
}
</script>

</body>
</html>
