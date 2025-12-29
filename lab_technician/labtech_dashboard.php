<?php
session_start();

// Protect page
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'lab technician') {
    header("Location: login.php");
    exit();
}

$user_role = $_SESSION['user_role'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Lab Technician Dashboard - IBBMS</title>
    <link rel="stylesheet" href="labtech_dashboard.css">
</head>

<body>

<div class="sidebar">
    <h2>Lab Technician Panel</h2>
    <a href="#" data-page="home">Home</a>
    <a href="#" data-page="record_test_result">Record Test Result</a>
<a href="#" data-page="send_test_result">Send Test Result</a>

    <a href="#" data-page="view_announcements">View Announcements</a>
    <a href="logout.php">Logout</a>
</div>

<div class="content">
    <div id="pageContent">
        <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
        <p>Select an operation from the menu.</p>
    </div>
</div>

<script>
// ====== MENU CLICK HANDLER ======
document.querySelectorAll(".sidebar a[data-page]").forEach(link => {
    link.addEventListener("click", function(e){
        e.preventDefault();
        const page = this.dataset.page;

        if(page === "view_announcements") {
            loadAnnouncements('<?php echo $user_role; ?>');
        } else if(page === "home") {
            document.getElementById("pageContent").innerHTML = `
                <h1>Welcome <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
                <p>Select an operation from the menu.</p>
            `;
        } else {
            loadPage(page + ".php");
        }
    });
});

// ====== LOAD PAGE INLINE ======
function loadPage(pageFile) {
    fetch(pageFile)
        .then(res => res.text())
        .then(html => {
            const content = document.getElementById("pageContent");
            content.innerHTML = html;

            // Execute scripts in loaded page
            const scripts = content.querySelectorAll("script");
            scripts.forEach(oldScript => {
                const newScript = document.createElement("script");
                newScript.textContent = oldScript.textContent;
                document.body.appendChild(newScript);
                oldScript.remove();
            });
        })
        .catch(() => {
            document.getElementById("pageContent").innerHTML = "<p style='color:red;'>Page not found.</p>";
        });
}

// ====== LOAD ANNOUNCEMENTS INLINE ======
function loadAnnouncements(role) {
    fetch("viewinfo.php?role=" + role)
    .then(res => res.text())
    .then(html => {
        document.getElementById("pageContent").innerHTML = html;
    })
    .catch(() => {
        document.getElementById("pageContent").innerHTML = "<p style='color:red;'>No announcements found.</p>";
    });
}
</script>

</body>
</html>
