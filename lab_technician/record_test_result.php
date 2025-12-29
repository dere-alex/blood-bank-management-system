<?php
session_start();
require_once "config.php";

// Only for logged-in lab technicians
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'lab technician') {

    echo "<p>Please log in as a Lab Technician.</p>";
    exit();
}
?>

<div class="test-card">
    <h3>Record Test Result</h3>
    <form id="recordTestForm">
        <label for="person_type">Type:</label>
        <select id="person_type" name="person_type" required>
            <option value="">--Select--</option>
            <option value="patient">Patient</option>
            <option value="donor">Donor</option>
        </select>

        <label for="person_id">Select Person:</label>
        <select id="person_id" name="person_id" required>
            <option value="">--Select Type First--</option>
        </select>

        <label for="test_name">Test Name:</label>
        <input type="text" id="test_name" name="test_name" required>

        <label for="result">Result:</label>
        <textarea id="result" name="result" required></textarea>

        <label for="test_date">Test Date:</label>
        <input type="date" id="test_date" name="test_date" required>

        <button type="submit">Save Test Result</button>
    </form>
    <div id="recordResultMsg"></div>
</div>

<style>
.test-card {
    background: #fff;
    padding: 20px;
    max-width: 500px;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}
.test-card h3 { margin-bottom: 15px; text-align: center; }
.test-card label { display: block; margin-top: 10px; }
.test-card select, 
.test-card input, 
.test-card textarea, 
.test-card button {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
.test-card button {
    background: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
    margin-top: 15px;
}
.test-card button:hover { background: #0056b3; }
#recordResultMsg { margin-top: 15px; font-weight: bold; text-align: center; }
</style>
<script>
// ===============================
// LOAD PATIENT / DONOR LIST
// ===============================
document.getElementById('person_type').addEventListener('change', function () {

    const type = this.value;
    const personSelect = document.getElementById('person_id');

    if (!type) {
        personSelect.innerHTML = "<option value=''>--Select Person--</option>";
        return;
    }

    personSelect.innerHTML = "<option>Loading...</option>";

    fetch("get_users.php?role=" + encodeURIComponent(type))
        .then(res => {
            if (!res.ok) throw new Error("Network error");
            return res.json();
        })
        .then(data => {
            if (data.length === 0) {
                personSelect.innerHTML = "<option>No records found</option>";
                return;
            }

            let options = "<option value=''>--Select--</option>";
            data.forEach(user => {
                options += `<option value="${user.id}">${user.fullname}</option>`;
            });
            personSelect.innerHTML = options;
        })
        .catch(err => {
            console.error(err);
            personSelect.innerHTML = "<option>Error loading persons</option>";
        });
});


document.getElementById("recordTestForm").addEventListener("submit", function(e){
    e.preventDefault();

    const msg = document.getElementById("recordResultMsg");

    const data = new URLSearchParams();
    data.append("person_type", document.getElementById("person_type").value);
    data.append("person_id", document.getElementById("person_id").value);
    data.append("test_name", document.getElementById("test_name").value);
    data.append("result", document.getElementById("result").value);

    fetch("save_test_result.php", {
        method: "POST",
        body: data
    })
    .then(res => res.text())
    .then(response => {
        msg.innerHTML = response;
        document.getElementById("recordTestForm").reset();
        document.getElementById("person_id").innerHTML =
            "<option value=''>--Select Person--</option>";
    })
    .catch(() => {
        msg.innerHTML = "<p style='color:red'>Failed to save test result</p>";
    });
});

</script>
