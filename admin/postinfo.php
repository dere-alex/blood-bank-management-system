<?php
session_start();
require_once "config.php";

/* ===============================
   ACCESS CONTROL – ADMIN ONLY
================================ */
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}



/* ===============================
   MESSAGE HOLDERS
================================ */
$error = "";
$success = "";

/* ===============================
   HANDLE FORM SUBMISSION
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title'] ?? "");
    $message     = trim($_POST['message'] ?? "");
    $target_role = $_POST['target_role'] ?? "all";
    $posted_by   = $_SESSION['user_id'];

    /* Validation: message or file required */
    if ($message === "" && (!isset($_FILES['file']) || $_FILES['file']['error'] === 4)) {
        $error = "Please provide a message or upload a file.";
    } else {

        $file_path = null;
        $file_type = null;
        $file_size = null;

        /* ===============================
           FILE UPLOAD HANDLING
        ================================ */
        if (isset($_FILES['file']) && $_FILES['file']['error'] === 0) {

            $allowed_types = ['pdf','png','jpg','jpeg','txt','docx'];
            $file_name = $_FILES['file']['name'];
            $file_tmp  = $_FILES['file']['tmp_name'];
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if (!in_array($file_ext, $allowed_types)) {
                $error = "Invalid file type. Allowed: pdf, png, jpg, jpeg, txt, docx";
            } else {

                $upload_dir = "uploads/info/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                $new_file_name = time() . "_" . basename($file_name);
                $target_path = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $target_path)) {
                    $file_path = $target_path;
                    $file_type = $file_ext;
                    $file_size = filesize($target_path);
                } else {
                    $error = "File upload failed.";
                }
            }
        }

        /* ===============================
           DATABASE INSERT
        ================================ */
        if ($error === "") {

            $sql = "INSERT INTO posts 
                    (title, message, file_path, file_type, file_size, target_role, posted_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                "ssssisi",
                $title,
                $message,
                $file_path,
                $file_type,
                $file_size,
                $target_role,
                $posted_by
            );

            if ($stmt->execute()) {
                $success = "Announcement posted successfully.";
            } else {
                $error = "Database error: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Announcement</title>
    <link rel="stylesheet" href="postinfo.css">
</head>
<body>

<div class="form-container">
    <h2>Post Announcement</h2>

    <?php if ($error): ?>
        <p id="msg" style="color:red;"><?php echo $error; ?></p>
    <?php elseif ($success): ?>
        <p id="msg" style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label for="title">Title (optional)</label>
        <input type="text" id="title" name="title">

        <label for="message">Message</label>
        <textarea id="message" name="message" rows="5"></textarea>

        <label for="file">Upload File</label>
        <input type="file" id="file" name="file" accept=".pdf,.png,.jpg,.jpeg,.txt,.docx">

        <label for="target_role">Target Audience</label>
        <select id="target_role" name="target_role">
            <option value="all">All Users</option>
            <option value="donor">Donors</option>
            <option value="patient">Patients</option>
            <option value="staff">Staff</option>
        </select>

        <button type="submit">Post Announcement</button>
    </form>
</div>

</body>
</html>
