<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Appointment</title>
    <link rel="stylesheet" href="make_donation.css">
</head>
<body>

<div class="form-box">
    <h2>Donation Appointment</h2>

    <form action="donation_appointment.php" method="POST">

        <label>Donation Date</label>
        <input type="date" name="donation_date" required>

        <label>Donation Time</label>
        <input type="time" name="donation_time" required>

        <label>Donation Center / Branch</label>
        <input type="text" name="donation_center" placeholder="Enter center name" required>

        <label>Last Donation Date</label>
        <input type="date" name="last_donation_date">

        <button type="submit">Book Appointment</button>
    </form>
</div>

</body>
</html>
