<?php
session_start();
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'hospital') {
    exit("Unauthorized");
}
?>

<link rel="stylesheet" href="blood_request.css">

<div class="box">
    <h2>Hospital Blood Request</h2>

    <!-- Success message -->
    <?php 
    if (!empty($_SESSION['msg'])) {
        echo "<p class='success'>{$_SESSION['msg']}</p>";
        unset($_SESSION['msg']);
    }
    ?>

    <form method="POST" action="hospital_request_handler.php">
        <label>Blood Group</label>
        <select name="blood_group" required>
            <option value="">-- Select Blood Group --</option>
            <option>A+</option><option>A-</option>
            <option>B+</option><option>B-</option>
            <option>O+</option><option>O-</option>
            <option>AB+</option><option>AB-</option>
        </select>

        <label>Quantity</label>
        <input type="number" name="quantity" min="1" required>

        <button type="submit">Submit Request</button>
    </form>
</div>
