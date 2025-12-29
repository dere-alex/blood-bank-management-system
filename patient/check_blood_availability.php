<div class="blood-card">
    <h3>Check Blood Availability</h3>
    <form id="dashboardBloodForm">
        <select id="blood_group" name="blood_group" required>
            <option value="">--Select Blood Group--</option>
            <option value="A+">A+</option>
            <option value="A-">A-</option>
            <option value="B+">B+</option>
            <option value="B-">B-</option>
            <option value="O+">O+</option>
            <option value="O-">O-</option>
            <option value="AB+">AB+</option>
            <option value="AB-">AB-</option>
        </select>
        <button type="submit">Check Availability</button>
    </form>
    <div id="bloodResult"></div>
</div>

<style>
.blood-card {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    max-width: 400px;
    margin: 20px auto;
    text-align: center;
}
.blood-card h3 { margin-bottom: 15px; }
.blood-card select, .blood-card button {
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
.blood-card button {
    background-color: #007bff;
    color: #fff;
    border: none;
    cursor: pointer;
}
.blood-card button:hover { background-color: #0056b3; }
#bloodResult { margin-top: 15px; font-weight: bold; }
</style>

<script>
document.getElementById('dashboardBloodForm').addEventListener('submit', function(e){
    e.preventDefault();
    const blood_group = document.getElementById('blood_group').value;
    const resultDiv = document.getElementById('bloodResult');

    fetch('check_blood.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `blood_group=${encodeURIComponent(blood_group)}`
    })
    .then(res => res.text())
    .then(data => { resultDiv.innerHTML = data; })
    .catch(err => { resultDiv.innerHTML = 'Error checking blood availability.'; });
});
</script>
