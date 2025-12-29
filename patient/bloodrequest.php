<!DOCTYPE html>
<html>
<head>
    <title>Request Blood Unit</title>
    <link rel="stylesheet" href="bloodrequest.css">
</head>
<body>

<div class="box">
    <h2>Request Blood Unit</h2>

    <label>Blood Type Needed</label>
    <select id="blood">
        <option value="" disabled selected>Select Blood Type</option>
        <option>A+</option><option>A-</option>
        <option>B+</option><option>B-</option>
        <option>AB+</option><option>AB-</option>
        <option>O+</option><option>O-</option>
    </select>

    <label>Units Required</label>
    <input type="number" id="units" min="1">

    <label>Hospital Name</label>
    <input type="text" id="hospital">

    <button onclick="submitRequest()">Submit</button>

    <p id="msg"></p>
</div>

<script>
function submitRequest() {
    if (blood.value === "") {
        msg.innerText = "Select blood type!";
        msg.style.color = "red";
        return;
    }
    if (units.value === "" || units.value <= 0) {
        msg.innerText = "Enter valid units!";
        msg.style.color = "red";
        return;
    }
    if (hospital.value.trim() === "") {
        msg.innerText = "Enter hospital name!";
        msg.style.color = "red";
        return;
    }

    msg.innerText = "Blood Request Sent Successfully!";
    msg.style.color = "green";
}
</script>

</body>
</html>
