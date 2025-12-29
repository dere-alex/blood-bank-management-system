<?php
session_start();
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['patient','donor','hospital'])) {
    exit("Unauthorized");
}

if (!empty($_SESSION['msg'])) {
    echo "<p style='color:green'>{$_SESSION['msg']}</p>";
    unset($_SESSION['msg']);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Give Feedback</title>
<style>
form { max-width: 500px; margin: 50px auto; display:flex; flex-direction:column; }
textarea { padding:10px; margin-bottom:15px; }
button { padding:10px; background-color:#007bff; color:#fff; border:none; cursor:pointer; }
button:hover { background-color:#0056b3; }
h2{text-align: center;}
</style>
</head>
<body>

<h2>Submit Feedback</h2>
<form method="POST" action="submit_feedback.php">
    <textarea name="feedback" rows="5" placeholder="Write your feedback here..." required></textarea>
    <button type="submit">Submit Feedback</button>
</form>

</body>
</html>
